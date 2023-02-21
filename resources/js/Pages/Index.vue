<template>
	<Layout>
		<header
			class="border position-fixed shadow-sm rounded mt-4 w-100 max-w-100 d-flex justify-content-between align-items-center px-3 bg-white"
			style="height: 4rem; max-width: 768px; z-index: 10;">
			<div class="fw-bold">
				<div>{{ user.name }}</div>
				<div class="text-muted"><small>{{ user.username }}</small></div>
			</div>

			<div>
				<Link as="button" method="delete" class="btn btn-secondary" :href="route('auth.destroy')">Logout</Link>
			</div>
		</header>

		<section style="padding-top: 6rem; padding-bottom: 6rem;">
			<div v-for="message in data.messages" class="card card-body w-75 mb-3"
				:class="{ 'ms-auto bg-primary text-white': user.id === message.user.id }">
				<div>{{ message.content }}</div>
				<div v-if="user.id !== message.user.id"><small>By <b>{{ message.user.name }}</b></small></div>
				<div><small>{{ message.created_at }}</small></div>
			</div>
		</section>

		<header class="border position-fixed d-flex justify-content-center rounded mt-4 w-100 bg-white"
			style="height: 4rem; max-width: 768px; z-index: 10; bottom: 1rem;">
			<div class="input-group">
				<input v-model="form.content" type="text" class="form-control" placeholder="Send a message...">
				<button class="btn btn-primary px-4" @click="submitForm">Send</button>
			</div>
		</header>

	</Layout>
	<div id="page-bottom"></div>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import route from 'ziggy-js';
import { Link } from '@inertiajs/vue3';
import { reactive, onMounted } from 'vue';


const props = defineProps({
	user: [Object, null],
	messages: Array,
});

const data = reactive({
	messages: props.messages,
});

let connection = null;

onMounted(() => {
	connection = new WebSocket('ws://localhost:81');
	connection.onopen = () => {
		connection.send(JSON.stringify({ user_id: props.user.id, action: 'register' }));
	};

	connection.onmessage = (event) => {
		let incoming = JSON.parse(event.data);

		if (incoming.event === 'newMessage') {
			data.messages.push(incoming.data);
			setTimeout(() => {
				document.getElementById('page-bottom').scrollIntoView()
			}, 200);
		}
	};

	setTimeout(() => {
		document.getElementById('page-bottom').scrollIntoView()
	}, 200);
});

const form = reactive({
	user_id: props.user.id,
	content: '',
	action: 'pushMessage',
});

const submitForm = () => {
	if (form.content.length >= 1 && form.content.length <= 255) {
		connection.send(JSON.stringify(form));
		form.content = '';
		setTimeout(() => {
			document.getElementById('page-bottom').scrollIntoView()
		}, 200);
	}
};
</script>
