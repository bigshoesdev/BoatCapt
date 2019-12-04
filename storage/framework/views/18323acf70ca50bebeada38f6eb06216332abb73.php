<?php $__env->startSection('title', 'Booking Complete'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <owner-booking-complete v-bind:bid-info="<?php echo e($bidInfo); ?>" v-bind:email="<?php echo e($email); ?>"></owner-booking-complete>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>