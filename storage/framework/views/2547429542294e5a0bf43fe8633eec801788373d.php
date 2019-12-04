<?php $__env->startSection('title', 'Trip Detail'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <owner-trip-detail v-bind:trip-info="<?php echo e($tripInfo); ?>" v-bind:is-admin="<?php echo e(isset($isAdmin)?$isAdmin:0); ?>"></owner-trip-detail>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>