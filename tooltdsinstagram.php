<?php
error_reporting(E_ERROR);
system('clear');
if (!file_exists('config.json')) {
	file_put_contents('config.json', json_encode(array('token' => '', 'cookie' => '', 'job' => 'like,likecmt,cmt,follow', 'like_delay' => 60, 'follow_delay' => 120, 'cmt_delay' => 30, 'like_cmt_delay' => 30)));
	echo "\033[1;33mVui lòng setup file config.json!\033[0m\r\n";
	exit;
}
$cookie = json_decode(file_get_contents('config.json'), true)['cookie'];
$token = json_decode(file_get_contents('config.json'), true)['token'];
$job = strtolower(json_decode(file_get_contents('config.json'), true)['job']);
$like_delay = (int)json_decode(file_get_contents('config.json'), true)['like_delay'];
$follow_delay = (int)json_decode(file_get_contents('config.json'), true)['follow_delay'];
$cmt_delay = (int)json_decode(file_get_contents('config.json'), true)['cmt_delay'];
$like_cmt_delay = (int)json_decode(file_get_contents('config.json'), true)['like_cmt_delay'];
$username_instagram = json_decode(curlGet('https://i.instagram.com/api/v1/accounts/edit/web_form_data/', $cookie), true)['form_data']['username'];
if ($username_instagram === null || empty($username_instagram) || strlen($username_instagram) < 1) {
	echo "\033[0;31mCookie instagram không chính xác!\033[0m\r\n";
	exit;
}
echo "\033[0;32mĐăng nhập instagram thành công!\033[0m\r\n";
if (json_decode(curlGet("https://traodoisub.com/api/?fields=profile&access_token=$token"), true)['success'] !== 200) {
	echo "\033[0;31mToken tds không chính xác!\033[0m\r\n";
	exit;
}
echo "\033[0;32mĐăng nhập tds thành công!\033[0m\r\n";
if (json_decode(curlGet("https://traodoisub.com/api/?fields=instagram_run&id=$username_instagram&access_token=$token"), true)['success'] !== 200) {
	echo "\033[0;31mKhông thể set nick username!\033[0m\r\n";
	exit;
}
$token_instagram = json_decode(curlGet("https://www.instagram.com/data/shared_data/", $cookie), true)['config']['csrf_token'];
while(true) {
	if (!stringContains($job, ',')) {
		if ($job === 'like') {
			$jobs = getJob('instagram_like', $token);
			if ($jobs > 0) {
				$job_random = $jobs[rand(0, count($jobs) - 1)];
				if (insLike($cookie, $token_instagram, explode('_', $job_random)[0])) {
					$xu_pending = postJob($token, 'INS_LIKE_CACHE', $job_random);
					if ($xu_pending !== null || !empty($xu_pending) || strlen($xu_pending) > 0) {
						echo "[$job_random] Đã gửi duyệt nhiệm vụ like, số xu đợi duyệt: $xu_pending\r\n";
						waitJob($like_delay);
					}
				}
			}
		} else if ($job === 'likecmt') {
			$jobs = getJob('instagram_likecmt', $token);
			if ($jobs > 0) {
				$job_random = $jobs[rand(0, count($jobs) - 1)];
				if (insLikeCMT($cookie, $token_instagram, explode('_', $job_random)[0])) {
					$xu_pending = postJob($token, 'INS_LIKECMT_CACHE', $job_random);
					if ($xu_pending !== null || !empty($xu_pending) || strlen($xu_pending) > 0) {
						echo "[$job_random] Đã gửi duyệt nhiệm vụ likecmt, số xu đợi duyệt: $xu_pending\r\n";
						waitJob($like_cmt_delay);
					}
				}
			}
		} else if ($job === 'follow') {
			$jobs = getJob('instagram_follow', $token);
			if ($jobs > 0) {
				$job_random = $jobs[rand(0, count($jobs) - 1)];
				if (insFollow($cookie, $token_instagram, explode('_', $job_random)[0])) {
					$xu_pending = postJob($token, 'INS_FOLLOW_CACHE', $job_random);
					if ($xu_pending !== null || !empty($xu_pending) || strlen($xu_pending) > 0) {
						echo "[$job_random] Đã gửi duyệt nhiệm vụ follow, số xu đợi duyệt: $xu_pending\r\n";
						waitJob($follow_delay);
					}
				}
			}
		}
	} else {
		foreach (explode(',', $job) as $item) {
			$item = strtolower($item);
			if ($item === 'like') {
				$jobs = getJob('instagram_like', $token);
				if ($jobs > 0) {
					$job_random = $jobs[rand(0, count($jobs) - 1)];
					if (insLike($cookie, $token_instagram, explode('_', $job_random)[0])) {
						$xu_pending = postJob($token, 'INS_LIKE_CACHE', $job_random);
						if ($xu_pending !== null || !empty($xu_pending) || strlen($xu_pending) > 0) {
							echo "[$job_random] Đã gửi duyệt nhiệm vụ like, số xu đợi duyệt: $xu_pending\r\n";
							waitJob($like_delay);
						}
					}
				}
			} else if ($item === 'likecmt') {
				$jobs = getJob('instagram_likecmt', $token);
				if ($jobs > 0) {
					$job_random = $jobs[rand(0, count($jobs) - 1)];
					if (insLikeCMT($cookie, $token_instagram, explode('_', $job_random)[0])) {
						$xu_pending = postJob($token, 'INS_LIKECMT_CACHE', $job_random);
						if ($xu_pending !== null || !empty($xu_pending) || strlen($xu_pending) > 0) {
							echo "[$job_random] Đã gửi duyệt nhiệm vụ likecmt, số xu đợi duyệt: $xu_pending\r\n";
							waitJob($like_cmt_delay);
						}
					}
				}
			} else if ($item === 'follow') {
				$jobs = getJob('instagram_follow', $token);
				if ($jobs > 0) {
					$job_random = $jobs[rand(0, count($jobs) - 1)];
					if (insFollow($cookie, $token_instagram, explode('_', $job_random)[0])) {
						$xu_pending = postJob($token, 'INS_FOLLOW_CACHE', $job_random);
						if ($xu_pending !== null || !empty($xu_pending) || strlen($xu_pending) > 0) {
							echo "[$job_random] Đã gửi duyệt nhiệm vụ follow, số xu đợi duyệt: $xu_pending\r\n";
							waitJob($follow_delay);
						}
					}
				}
			}
		}
	}
}
function postJob($token, $type, $id) {
	return json_decode(curlGet("https://traodoisub.com/api/coin/?type=$type&id=$id&access_token=$token"), true)['data']['pending'];
}
function stringContains($haystack, $needle) {
	return strpos($haystack, $needle) !== false;
}
function insLike($cookie, $token, $id) {
	return (bool)(json_decode(curlPost("https://i.instagram.com/api/v1/web/likes/$id/like/", $cookie, $token), true)['status'] === 'ok');
}
function insFollow($cookie, $token, $id) {
	return (bool)(json_decode(curlPost("https://i.instagram.com/api/v1/web/friendships/$id/follow/", $cookie, $token), true)['status'] === 'ok');
}
function insLikeCMT($cookie, $token, $id) {
	return (bool)(json_decode(curlPost("https://i.instagram.com/api/v1/web/comments/like/$id/", $cookie, $token), true)['status'] === 'ok');
}
function insCMT($cookie, $token, $id, $cmt) {
	$cmt = urlencode($cmt);
	return (bool)(json_decode(curlPost2("https://i.instagram.com/api/v1/web/comments/$id/add/", $cookie, $token, "comment_text=$cmt"), true)['status'] === 'ok');
}
function getJob($type, $token) {
	$arr = array();
	$json = json_decode(curlGet("https://traodoisub.com/api/?fields=$type&access_token=$token"), true)['data'];
	foreach ($json as $item) {
		$arr[] = $item['id'];
	}
	return $arr;
}
function waitJob($s) {
	for ($i = $s; $i > 0; $i--) {
		echo "\r\033[1;33mĐang delay job, còn $i giây để chuyển job...\033[0m";
		sleep(1);
		if ($i === 1) echo "\r";
	}
}
function curlPost($uri, $cookie = null, $token = null) {
	$curl = curl_init();
	if ($cookie === null || empty($cookie) || strlen($cookie) < 1) {
		$headers = array(
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36',
			'x-ig-app-id: 936619743392459',
			'x-instagram-ajax: 1006292718'
		);
	} else {
		$headers = array(
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36',
			'x-ig-app-id: 936619743392459',
			'x-instagram-ajax: 1006292718',
			'Cookie: ' . $cookie,
			'x-csrftoken: ' . $token
		 );
	}
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $uri,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_HTTPHEADER => $headers,
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
function curlPost2($uri, $cookie, $token, $data) {
	$curl = curl_init();
	$headers = array(
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36',
		'x-ig-app-id: 936619743392459',
		'Cookie: ' . $cookie,
		'x-csrftoken: ' . $token
	 );
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $uri,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_POSTFIELDS => $data,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_HTTPHEADER => $headers,
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
function curlGet($uri, $cookie = null) {
	$curl = curl_init();
	if ($cookie === null || empty($cookie) || strlen($cookie) < 1) {
		$headers = array(
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36',
			'x-ig-app-id: 936619743392459',
		);
	} else {
		$headers = array(
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36',
			'x-ig-app-id: 936619743392459',
			'Cookie: ' . $cookie,
		 );
	}
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $uri,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => $headers,
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
?>
