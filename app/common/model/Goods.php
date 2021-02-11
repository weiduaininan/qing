<?php  
namespace app\common\model;
use think\facade\Db;
use app\common\model\Category as CategoryModel;
class Goods extends \think\Model


{	


	/* 商量列表,此方法包含了列表上面的搜索部分

	** GET方法获取url参数

	** 如果选择了某个商品分类，会出现该商品分类下包含子分类在内的所有的商品

	** 状态,数量,精品，新品,每页显示多少个

	** by qing

	 */


	public function search($cate_id,$goods_status,$goods_name,$page='10'){


        //根据url传过来的分类id，找出该分类下面的所有子分类id

        if(!empty($cate_id)){

        	$cates=Db::name('Category')->field('id,parent_id,cate_name')->select();

			$_cateChilerenId=(new CategoryModel())->getChildrenIdStr($cates,$cate_id);

			$cateChilerenId=$cate_id.','.$_cateChilerenId;//先把当前的id放到字符串中

		}
		

		$where=[];

		if($cate_id){
			$where[] = [
				['goods_cate_id', 'in', $cateChilerenId],
			];
		}

		if(!empty($goods_status)){
			$where[] = [
				['goods_status', '=', $goods_status],
			];
		}

		if(!empty($goods_name)){
			$where[] = [
				['goods_name', 'like', '%'.$goods_name.'%'],
			];
		}


		$goodsData=Db::name('goods')->alias('a')->join('category b','a.goods_cate_id=b.id')->field('a.*,b.cate_name')->where($where)->order('listorder asc')->order('goods_id desc')->paginate(['list_rows'=> $page,'query'=>request()->param()]);


        return $goodsData;		


	}




	public function searchGoodsCateIdAttr($query, $value)
	{
		$query->where('goods_cate_id','in', $value);
	}

	public function searchGoodsStatusAttr($query, $value)
	{
		$query->where('goods_status','=', $value);
	}

	public function searchGoodsNameAttr($query, $value)
	{
		$query->where('goods_name','like', '%'.$value.'%');
	}

	//搜索器的使用
	public function search1($cate_id,$goods_status,$goods_name,$page='10'){


        //根据url传过来的分类id，找出该分类下面的所有子分类id
		$cateChilerenId='';
        if(!empty($cate_id)){

        	$cates=Db::name('Category')->field('id,parent_id,cate_name')->select();

			$_cateChilerenId=(new CategoryModel())->getChildrenIdStr($cates,$cate_id);

			$cateChilerenId=$cate_id.','.$_cateChilerenId;//先把当前的id放到字符串中

		}
		

		if(empty($cate_id)){
			$goodsData=Goods::name('goods')->alias('a')->join('category b','a.goods_cate_id=b.id')->field('a.*,b.cate_name')->order('listorder asc')->order('goods_id desc')->withSearch(['goods_status','goods_name'],[
				'goods_status'=>$goods_status,
				'goods_name'=>$goods_name
			])->paginate(['list_rows'=> $page,'query'=>request()->param()]);
		}

		if(!empty($cate_id)){
			$goodsData=Goods::name('goods')->alias('a')->join('category b','a.goods_cate_id=b.id')->field('a.*,b.cate_name')->order('listorder asc')->order('goods_id desc')->withSearch(['goods_cate_id','goods_status','goods_name'],[
				'goods_cate_id'=>$cateChilerenId,
				'goods_status'=>$goods_status,
				'goods_name'=>$goods_name
			])->paginate(['list_rows'=> $page,'query'=>request()->param()]);
		}

		
		 
		//echo Goods::getLastSql();

        return $goodsData;		


	}






}



?>