<?php $__env->startSection('title', 'Captain Trip Detail'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <captain-trip-detail v-bind:trip-info="<?php echo e($tripInfo); ?>"></captain-trip-detail>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>