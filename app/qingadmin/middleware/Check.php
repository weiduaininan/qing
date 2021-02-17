<?php
declare (strict_types = 1);

namespace app\qingadmin\middleware;

class Check {
	/**
	 * 处理请求
	 *
	 * @param \think\Request $request
	 * @param \Closure       $next
	 * @return Response
	 */
	public function handle($request, \Closure $next) {
		if (strpos($_SERVER['REQUEST_URI'], '/qingadmin/scripts') === false) {
			if (empty(session('adminSessionData')) && !preg_match('/login/', $request->pathinfo())) {
				// echo "<meta http-equiv='Refresh' content='0;URL=/qingadmin/index/welcome'>";die;
				return redirect((string) url('login/index'));
			}
		}
		return $next($request);
	}
}
