<?php

namespace App\Http\Controllers\API;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    { 
        
        $blogs = Blog::all();
        return response()->json([
            'status'=> 200,
            'bloglists'=>$blogs,
        ]);
    }
    public function allblogs(){
        
        $blogs = Blog::where('status',0)->get();
        
        return response()->json([
            'status'=> 200,
            'bloglists'=>$blogs,
        ]);
    }
    public function singleblog($slug){
        $singleblog = Blog::where('slug',$slug)->where('status',0)->first();
        if($singleblog)
        {
            return response()->json([
                'status'=> 200,
                'singleblog' => $singleblog,
            ]);
        }
        else
        {
            return response()->json([
                'status'=> 404,
                'message' => 'blog notFound',
            ]);
        }
    }

    public function store(Request $request)
    { 
        //$img_tmp =$request->file('image')->store('blog');
        $data = $request->all();
        $validator = Validator::make($request->all(),[
            'title'=>'required|max:191',
            'slug'=>'required|max:191',
            'body'=>'required',
            'image'=>'required|image|mimes:jpeg,jpg,png'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'validate_err'=> $validator->messages(),
            ]);
        }
        else
        {
            $blog = new Blog;
            $blog->title = $data['title'];
            $blog->slug = Str::slug($data['slug'], '-');
            $blog->body = $data['body'];
            $blog->status = $data['status'] == true? "1" :"0" ;
            
         if($request->hasFile('image')){
            $img_tmp =$request->file('image');
            $filename =time() . '.' .$img_tmp->getClientOriginalExtension();
            //$path = $img_tmp->store('public');
            $location=('blog/' .$filename);
            Image::make($img_tmp)->resize(1600,1066)->save($location);
            $blog->image = $location ;
         }

            $blog->save();

            return response()->json([
                'status'=> 200,
                'blog'=>$blog,
                'message'=>'Blog Added Successfully',
            ]);
        }

    }

    public function edit($id)
    {
        $blog = Blog::find($id);
        if($blog)
        {
            return response()->json([
                'status'=> 200,
                'blog' => $blog,
            ]);
        }
        else
        {
            return response()->json([
                'status'=> 404,
                'message' => 'blog notFound',
            ]);
        }

    }

    public function update(Request $request ,$id)
    { 
            $data = $request->all();
        //echo "<pre>";print_r($data);die;
        $validator = Validator::make($data,[
            'title'=>'required|max:191',
            'slug'=>'required|max:191',
            'body'=>'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'validate_err'=> $validator->messages(),
            ]);
        }
        else
        {
            $blog = Blog::find($id);
            if($blog)
            {
                $blog['title'] = $data['title'];
                $blog['slug'] = Str::slug($data['slug'], '-');
                $blog['body'] = $data['body'];
                $blog['status'] = $data['status'] == true? "1" :"0" ;
                
             if($request->hasFile('image')){
                $path = $blog->image;
                if(file_exists($path)){
                    unlink($path);
                }

                $img_tmp =$request->file('image');
               $filename =time() . '.' .$img_tmp->getClientOriginalExtension();
               //$path = $img_tmp->store('public');
                $location=('blog/' .$filename);
                Image::make($img_tmp)->resize(1600,1066)->save($location); 
                 $blog->image = $location ;
             }
                $blog->update();

                return response()->json([
                    'status'=> 200,
                    'message'=>'Blog Updated Successfully',
                ]);
            }
            else
            {
                return response()->json([
                    'status'=> 404,
                    'message' => 'Blog not Found',
                ]);
            }
        }
    
 }
    public function destroy($id)
    {
        $blog = Blog::find($id);
        if($blog)
        {
            $blog->delete();
            return response()->json([
                'status'=> 200,
                'message'=>'blog Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status'=> 404,
                'message' => 'blog not Found',
            ]);
        }
    }
}