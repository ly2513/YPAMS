<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
/**
* User: yongli
* Date: 17/5/17
* Time: 11:05
* Email: yong.li@szypwl.com
* Copyright: 深圳优品未来科技有限公司
*/
An uncaught Exception was encountered

Type:        <?php echo get_class($exception), "\n"; ?>
Message:     <?php echo $message, "\n"; ?>
Filename:    <?php echo $exception->getFile(), "\n"; ?>
Line Number: <?php echo $exception->getLine(); ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === true) : ?>

    Backtrace:
    <?php foreach ($exception->getTrace() as $error): ?>
        <?php if (isset($error['file'])) : ?>
            <?php echo trim('-'. $error['line'] .' - '. $error['file'] .'::'. $error['function']) ."\n" ?>
        <?php endif ?>
    <?php endforeach ?>

<?php endif ?>
