<script>
	import DogToy from './DogToy.svelte';
	export let name;
	export let count = 0;
	function add(){
		count++;
	}
	const src='/438526.png';
	let string = `this string contains some <strong>HTML!!!</strong>`;
</script>

<main>
	<h1>Hello {name}!</h1>
	<div class="mony-container">
		<h2>You have </h2>
		<p >${count}</p>
		<img alt='a dog' src={src}>
		<button on:click={add}>Add</button>
		<p>{@html string}</p>
	</div>
	<DogToy />
</main>

<style>
	.mony-container{
		display: flex;
		flex-direction: column;
	}
	.mony-container img{
		width: 10rem;
		height: auto;
		margin: 1rem auto 0rem auto;
	}
	.mony-container button{
		margin: 1rem auto 0rem auto;
	}
	main {
		text-align: center;
		padding: 1em;
		max-width: 240px;
		margin: 0 auto;
	}

	h1 {
		color: #ff3e00;
		text-transform: uppercase;
		font-size: 4em;
		font-weight: 100;
	}

	@media (min-width: 640px) {
		main {
			max-width: none;
		}
	}
</style>