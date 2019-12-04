<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:dark="true" v-bind:param="<?php echo e(json_encode(['avatar' => null, 'searchable' => true, 'login' => false])); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if($errors->any()): ?>  
    <login v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:param="<?php echo e(json_encode(['email' => old('email')])); ?>"></login>
    <?php else: ?>
    <login v-bind:param="<?php echo e(json_encode(['email' => ''])); ?>"></login>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>