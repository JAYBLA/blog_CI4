<?php 
 
namespace App\Controllers;
  
use CodeIgniter\Controller;
use App\Models\BlogModel;
  
class BlogController extends Controller
{
  
    public function index()
    {    
        $model = new BlogModel();
  
        $data['articles'] = $model->orderBy('id', 'DESC')->findAll();
         
        return view('dashboard/articles', $data);
    }    
 
  
    public function store()
    {  
        helper(['form', 'url']);
          
        $model = new BlogModel();
         
        $data = [
            'title' => $this->request->getVar('txttitle'),
            'description'  => $this->request->getVar('txtdescription'),
            ];
        $save = $model->insert_data($data);
        if($save != false)
        {
            $data = $model->where('id', $save)->first();
            
            echo json_encode(array("status" => true , 'data' => $data));
        }
        else{
            echo json_encode(array("status" => false , 'data' => $data));
        }
    }
  
    public function edit($id = null)
    {
       
     $model = new BlogModel();
     
     $data = $model->where('id', $id)->first();
      
    if($data){
            echo json_encode(array("status" => true , 'data' => $data));
        }else{
            echo json_encode(array("status" => false));
        }
    }
  
    public function update()
    {  
  
        helper(['form', 'url']);
          
        $model = new BlogModel();
 
        $id = $this->request->getVar('hdnArticleId');
 
         $data = [
            'title' => $this->request->getVar('txttitle'),
            'description'  => $this->request->getVar('txtdescription'),            
        ];
 
        $update = $model->update($id,$data);
        if($update != false)
        {
            $data = $model->where('id', $id)->first();
            echo json_encode(array("status" => true , 'data' => $data));
        }
        else{
            echo json_encode(array("status" => false , 'data' => $data));
        }
    }
  
    public function delete($id = null){
        $model = new BlogModel();
        $delete = $model->where('id', $id)->delete();
        if($delete)
        {
           echo json_encode(array("status" => true));
        }else{
           echo json_encode(array("status" => false));
        }
    }
}
