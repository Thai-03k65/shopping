<?php

/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các kha bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'shopecom_shop1e' );

/** Username của database */
define( 'DB_USER', 'shopecom_shop1e' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', 'CZMGKG6Ja' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để to bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này s buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '2^p8Ql)b;TKR(:7tT:Q?%cDVweTt`yJ_XKwkN5tyipK~l:lg)>U@t{?J-, yr1vY' );
define( 'SECURE_AUTH_KEY',  'Ra+a{wDu_`5$1z%op9p?>u~Epb?NJP!  =HAB|sG7gwG^S#~~fC=;:Wt90?K4-j3' );
define( 'LOGGED_IN_KEY',    'XV,~{^a]N+j]kNjyff`lu2of4bIG:aZb;ZCf);VIAS<;p#9>GcWGX3?8+>|~O~kz' );
define( 'NONCE_KEY',        '4g}g(Q?Q~vT:H&0ntt2_7/71tgN`~DfOg?u)KU,&YwqLtTL){X`B.mye43mgY)_H' );
define( 'AUTH_SALT',        '-tcY+n,S?1o#(FE=wr/Nik6b.CTWT1@NA!oexCe}R[@i)}L*qWe!Sr0f+)haXN,5' );
define( 'SECURE_AUTH_SALT', '/~a4K_:F%BBO1f=$sbYa;TFBa:v,J ,$N1.GM^+[hK~G&(EE#:1^f v8$/;w oQV' );
define( 'LOGGED_IN_SALT',   'XB>Pm3)%=#q7TbKC21 /s#TWOLyt>L C2*%2];cK{*IqNxdan.qi>@5%!_W1LM,6' );
define( 'NONCE_SALT',       'd;eaLT-K#9a:iS-@Xs=o%Z_MRXed(e)uRZ(Q`{zf!*ISKD]=]L$F-x{5E22Ak0ww' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * ặt tiền tố cho bảng gip bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
define('WP_MEMORY_LIMIT', '256M');
$table_prefix = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thng báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình pht triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể s dụng khi debug, hãy xem ti Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa t phần này trở xuống. Chc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt ối đến thư mục cài đt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
