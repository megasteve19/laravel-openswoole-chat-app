<template>
	<Layout>
		<div class="d-flex justify-content-center align-items-center w-100 min-vh-100 px-3">
			<div class="card px-4 py-3 rounded-4 shadow-sm">
				<div class="card-body">
					<form @submit.prevent="submitForm">
						<div class="mb-3">
							<label for="username" class="form-label">Username</label>
							<input v-model="form.username" id="username" type="text" class="form-control"
								placeholder="username" autocomplete="off">

							<div v-if="form.errors.username" class="invalid-feedback d-block">
								{{ form.errors.username }}
							</div>
						</div>
						<button type="submit" class="btn btn-primary w-100">Login</button>
					</form>
				</div>
			</div>
		</div>
	</Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import route from 'ziggy-js';

const form = useForm({
	username: null,
});

const submitForm = () => {
	form.post(route('auth.store'), { preserveScroll: true, preserveState: true });
};
</script>
