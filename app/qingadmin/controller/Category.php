<?php  

namespace app\qingadmin\controller;
use app\common\model\Category as CategoryModel;
use think\facade\Db;
class Category extends  Base

{

    
	public function index(){

        $categorys =(new CategoryModel())->getTree();

        return view('',[
            'categorys'=>$categorys,
        ]);


    }
    
    //搜索
    public function search(){
        $where=[];
		$search_key = input('cate_name');

		//如果有搜索
		if($search_key){
			$where[] = [
				['cate_name', 'like', '%'.$search_key.'%'],
			];
			
		}
        $categoryData = Db::name('category')->field('id,parent_id,cate_name,listorder,status')->
                        where($where)->where('status',1)->order('listorder asc')->select()->toArray();

        return view('',[
            'categoryData'=>$categoryData,
            'search_key'=>$search_key
        ]);
    }



    //分类添加

	public function add(){

        $categoryTree =(new CategoryModel())->getTree();

        $categoryData=Array(
            'id' =>'0',
            'thumb' =>'',
        );
        $typeData=Db::name('type')->field('id,type_name')->select();

        //处理添加操作
        if(request()->isPost()) {

            $data = input('post.');
            
            $catenameArr=explode(',',$data['cate_name']);
            foreach($catenameArr as $k=>$v){
                if(empty($v)){
                    continue;
                }
                $data['cate_name']=$v;
                $res=Db::name('category')->insert($data);
            }
            if($res){
                return alert('操作成功！','index',6);
            }else{
                return alert('操作失败！','index',5);
            }   

        }

       

        return view('', [

            'categoryData'=> $categoryData,
            'categoryTree'=>$categoryTree,
            'parent_id'=>0,
            'typeData'=>$typeData

        ]);


	}




    //编辑页面
    public function edit() {
        $id=$this->request->param('id','intval');

        if(empty($id)) {

            return alert('参数不合法','index',5);

        }


        $categoryData=Db::name('category')->find($id);

        $categoryTree =(new CategoryModel())->getTree();
        
        $typeData=Db::name('type')->field('id,type_name')->select();
        return view('', [

            'categoryTree'=> $categoryTree,
            'categoryData' => $categoryData,
            'typeData'=>$typeData

        ]);


    }


    public function update() {

        if(request()->isPost()) {

            $data=input('post.');

            $res =Db::name('category')->update($data);
        }
        

        if($res) {

            return alert('操作成功','index',6);

        }else {

            return alert('更新失败或者没有数据更新','index',5);

        }



    }



    

    //删除栏目的同时，删除该栏目的所有子类以及所属商品
    public function del(){
        $id=input('id');

        //得到所有子类ID
        $data=Db::name('category')->field('id,parent_id')->select();
        $childStr=(new CategoryModel())->getChildrenIdStr($data,$id);


        Db::name('category')->whereIn('id',$childStr)->delete();
        //Db::name('goods')->whereIn('goods_cate_id',$v)->delete();//删除商品

        Db::name('category')->delete($id);
        return alert('删除成功','index',6);



    }


     //通过父级id获取子类
     public function ajaxGetCateByP(){
        $parent_id=request()->param('parent_id');
        $categoryData =Db::name('category')->field('id,cate_name,parent_id')->where('parent_id',$parent_id)->select();
        return json($categoryData);
    }




}



?>