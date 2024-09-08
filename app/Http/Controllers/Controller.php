<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    //Function to fetch all pages
    public function fetchPage($url)
    {
        $response = Http::get($url);
        $result = $response->getBody()->getContents();
        return json_decode($result);
    }

    public function articles(Request $request)
    {
        $data = [
            'success' => true,
            'data' => null,
            'errors' => []
        ];

        try{
            $author = $request->get('author');
            $page = $request->get('page');
            $url = 'https://jsonmock.hackerrank.com/api/articles';
            if($author){
                $url = $url .'?author='.$author;
            }
    
            //Fetch url and get total_pages
            $num_pages = Http::get($url)['total_pages'];
           
            //Arrays
            $array = [];
            $tmp = [];
    
            //Get all elements from Api
            for($i=0; $i<$num_pages; $i++){
                $url = 'https://jsonmock.hackerrank.com/api/articles';
                if($author){
                    $url = $url .'?author='.$author;
                    $url = $url .'&page='.$i+1;
                }else{
                    $url = $url .'?page='.$i+1;
                }
                array_push($array, $this->fetchPage($url));
            }
    
            //Loop and get book with condition, return array of elements
            foreach($array as $item){
                foreach($item->data as $book){
                    if($book->title){
                        array_push($tmp, $book->title);
                    }
                    if($book->story_title){
                        array_push($tmp, $book->story_title);
                    }
                }
            }
        }catch(Exception $e){
            $data['success'] = false;
            $data['errors'] = 'Si è verificato un errore, contattare l\' assistenza';
        }
        
        $data['data'] = $tmp;
        return new JsonResponse($data);
    }

    public function getAuthors()
    {
        $data = [
            'success' => true,
            'data' => null,
            'errors' => []
        ];

        try{
            //Fetch url and get total_pages
            $url = 'https://jsonmock.hackerrank.com/api/articles';
            $num_pages = Http::get($url)['total_pages'];
           
            //Arrays
            $array = [];
            $tmp = [];
    
            //Get all elements from Api
            for($i=0; $i<$num_pages; $i++){
                $url = 'https://jsonmock.hackerrank.com/api/articles';
                $url = $url .'?page='.$i+1;
                array_push($array, $this->fetchPage($url));
            }
    
            foreach($array as $item){
                foreach($item->data as $book){
                    array_push($tmp, $book->author);
                }
            }
            $collect = collect($tmp);
        }catch(Exception $e){
            $data['success'] = false;
            $data['errors'] = 'Si è verificato un errore, contattare l\' assistenza';
        }

        $data['data'] = $collect->unique();

        //Return Json Response
        return new JsonResponse($data);
    }

}
