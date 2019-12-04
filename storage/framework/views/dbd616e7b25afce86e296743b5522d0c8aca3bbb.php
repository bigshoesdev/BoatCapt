<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $__env->yieldContent('title', 'Lander'); ?> | BoatCaptain</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="BoatCaptain">
        <?php echo $__env->yieldContent('meta'); ?>

        <link rel="icon" href="<?php echo e(url('/favicon.ico')); ?>">

        <!-- stylesheets -->
        <link href="<?php echo e(url('/public/css/app.css')); ?>" rel=stylesheet >
        <?php echo $__env->yieldContent('styles'); ?>  

        <?php if( App::environment() === 'local' ): ?>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_API_KEY')); ?>&libraries=places"></script>  
        <?php endif; ?>  
        
        <script>
            var URL = {
                'base' : '<?php echo e(url('/')); ?>',
                'current' : '<?php echo e(url()->current()); ?>',
                'full' : '<?php echo e(url()->full()); ?>',
                'previous': '<?php echo e(url()->previous()); ?>'
            };
            var token = '<?php echo e(csrf_token()); ?>';
        </script>
    </head>
    <body>
        <div id="app">   
            <?php echo $__env->yieldContent('header'); ?>               
            <div class="aside-overlay"></div>
            <?php if(!Auth::check()): ?>
            <page-menu></page-menu>   
            <?php elseif(Auth::user()->role == '1001'): ?>  
            <admin-menu></admin-menu>   
            <?php elseif(Auth::user()->role == '1002'): ?>  
            <owner-menu></owner-menu>   
            <?php elseif(Auth::user()->role == '1003'): ?>  
            <captain-menu></captain-menu>        
            <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
            <page-footer></page-footer> 
        </div>
        <?php echo $__env->yieldContent('scripts'); ?>
        <script src="<?php echo e(url('/public/js/app.js')); ?>"></script>
    </body>
</html>