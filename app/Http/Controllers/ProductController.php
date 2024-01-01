<?php

/**
 * ProductController class handles product-related operations.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use League\Csv\Reader;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Constructor to ensure only admin or staff users have access.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __construct()
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole("admin") || !$user->hasRole("staff")) {
            return redirect()
                ->route("home")
                ->with(
                    "error",
                    "Unauthorized access: Admin or Staff role required."
                );
        }
    }

    

    /**
     * Display a list of products based on filters.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        
        $status = $request->input("status", "all");
        $search = $request->input("search");

        $query = Product::query();

        $query
            ->when($status !== "all", function ($query) use ($status) {
                return $query->where("status", $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("model", "like", "%" . $search . "%")
                        ->orWhere("description", "like", "%" . $search . "%");
                });
            });

        $products = $query->paginate(100);

        $brands = Brand::all();
        
        $categories = Category::all();

        return view(
            "products.index",
            compact("products", "brands", "categories")
        );
    }
    public function import(Request $request)
{
    $validator = Validator::make($request->all(), [
        'file' => 'required|file|mimes:csv,txt',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->route('products.index')
            ->with('failure', $validator->errors());
    }

    $csv = $request->file('file');
    $reader = Reader::createFromPath($csv->getPathname(), 'r');

    $records = $reader->getRecords();

    $uploadedProducts = [];

    foreach ($records as $row) {
        $purchasePrice = $this->validateDecimal($row[3]);
        $retailPrice = $this->validateDecimal($row[4]);
        $currentPrice = $this->validateDecimal($row[5]);

        // Check if any of the decimal values failed validation
        if ($purchasePrice === null || $retailPrice === null || $currentPrice === null) {
            // Log an error or handle it based on your application's requirements
            continue; // Skip this row and move to the next one
        }

        $modelData = [
            'name' => $row[0],
            'photo' => $row[1] ?? null,
            'model' => $row[2] ?? null,
            'purchase_price' => $purchasePrice,
            'retail_price' => $retailPrice,
            'current_price' => $currentPrice,
            'quantity' => $row[6],
        ];

        // Check if the photo column is present and not empty
        if (!empty($row[1])) {
            $photoIndex = intval($row[1]) - 1;

            // Check if the 'photos' array is set and has an element at the specified index
            if ($request->hasFile('photo') && isset($request->file('photo')[$photoIndex])) {
                $photo = $request->file('photo')[$photoIndex];
                $filename = $photo->store('photo', 'public');
                $modelData['photo'] = $filename;
            } else {
                // Handle the case where the 'photos' array or the specified index is not set
                // Log an error or take appropriate action based on your application's requirements.
            }
        }

        $uploadedProducts[] = $modelData;
    }

    // Batch insert the products
    Product::insert($uploadedProducts);

    // Retrieve products, brands, and categories
    $products = Product::all(); // Replace with your actual query to get products
    $brands = Brand::all(); // Replace with your actual query to get brands
    $categories = Category::all(); // Replace with your actual query to get categories
    $products = Product::paginate(100);

    return view('products.index', ['products' => $products, 'brands' => $brands, 'categories' => $categories, 'uploadedProducts' => $uploadedProducts]);
}
    
    private function validateDecimal($value)
    {
        $trimmedValue = trim($value);
    
        // Ensure that the trimmed value is numeric
        return is_numeric($trimmedValue) ? $trimmedValue : null;
    }
    
    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        
        
        $brands = Brand::all(); // Assuming you have a Brand model

        $categories = Category::all();

        return view("products.create", compact("brands", "categories"));
    }

    
    /**
     * Store a newly created product in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "brand_id" => "nullable|string|max:255",
            "category_id" => "nullable|string|max:255",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "model" => "nullable|string|max:255",
            "purchase_price" => "required|numeric|between:0.00,9999999.99",
            "retail_price" => "required|numeric|between:0.00,9999999.99",
            "current_price" => "required|numeric|between:0.00,9999999.99",
            "quantity" => "required|integer",
            "status" => "nullable|string|max:255",
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route("products.index")
                ->with("failure", $validator->errors());
        }

        // Handle file upload if a photo is provided
        if ($request->hasFile("photo")) {
            $file = $request->file("photo");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $file->storeAs("public/photos", $fileName); // Store in storage/app/public/photos
        }

        Product::create($request->all());

        return redirect()
            ->route("products.index")
            ->with("success", "Product created successfully!");
    }

    /**
     * Display the specified product.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view("products.show", compact("product"));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        
        $product = Product::find($id);
        
        $brands = Brand::all(); 
       
        $categories = Category::all();
        // Pass the data to the view
        return view(
            "products.edit",
            compact("product", "brands", "categories")
        );
    }

    /**
     * Update the specified product in the database.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        
        $validator = Validator::make($request->all(), [
            "brand_id" => "nullable|string|max:255",
            "category_id" => "nullable|string|max:255",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "model" => "nullable|string|max:255",
            "purchase_price" => "required|numeric|between:0.00,9999999.99",
            "retail_price" => "required|numeric|between:0.00,9999999.99",
            "current_price" => "required|numeric|between:0.00,9999999.99",
            "quantity" => "required|integer",
            "status" => "nullable|string|max:255",
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route("products.index")
                ->with("failure", $validator->errors());
        }

        // Handle file upload if a new photo is provided
        if ($request->hasFile("photo")) {
            $file = $request->file("photo");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $file->storeAs("public/photos", $fileName); // Store in storage/app/public/photos
            $product->update(["photo" => $fileName]);
        }

        $product->update($request->all());

        return redirect()
            ->route("products.index")
            ->with("success", "Product updated successfully!");
    }

    /**
     * Remove the specified product from the database.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route("products.index")
            ->with("success", "Product deleted successfully!");
    }
}
