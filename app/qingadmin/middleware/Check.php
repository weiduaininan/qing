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
		if (empty(session('adminSessionData')) && !preg_match('/login/', $request->pathinfo())) {
			return redirect((string) url('login/index'));
		}
		return $next($request);
	}
}
