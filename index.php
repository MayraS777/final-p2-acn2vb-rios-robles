<?php
require_once("./components/styleSetup.php");
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Concesionaria R|R</title>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="<?= $_GET['tema'] ?? 'claro' ?>">

	<?php require("./components/header.php"); ?>

	<div class="container mt-3 text-center">

		<form id="filterForm" class="d-flex justify-content-center gap-2 mb-3">
			<input id="nombreInput" type="text" name="nombre" placeholder="Buscar Marca..." class="form-control w-25">
			<select id="categoriaSelect" name="categoria" class="form-select w-25">
				<option value="">Todas las categorías</option>
				<option value="Alta Gama">Alta Gama</option>
				<option value="Media Gama">Media Gama</option>
			</select>
			<button class="btn btn-primary" type="submit">Filtrar</button>
		</form>

		<button id="temaBtn" class="btn btn-primary mb-3">Cambiar Tema</button>

		<div id="cardsContainer" class="row justify-content-center"></div>

		<div class="d-flex justify-content-center gap-2 mt-3">
			<button class="btn btn-secondary" id="prevBtn">Anterior</button>
			<button class="btn btn-secondary" id="nextBtn">Siguiente</button>
		</div>

		<div id="msg" class="mt-3"></div>

		<div class="conteiner2 mt-4">
			<h4>Agregar nuevo ítem</h4>
			<form id="addForm" method="POST" class="formulario">
				<input name="name" type="text" placeholder="Nombre" required class="form-control mb-2">
				<input name="categoria" type="text" placeholder="Categoria" required class="form-control mb-2">
				<input name="descrip" type="text" placeholder="Descripcion" required class="form-control mb-2">
				<input name="url" type="text" placeholder="URL imagen (opcional)" class="form-control mb-2">
				<input name="link" type="text" placeholder="Link (opcional)" class="form-control mb-2">
				<button class="btn btn-primary" type="submit">Agregar</button>
			</form>
		</div>

	</div>

	<?php require("./components/footer.php"); ?>

	<script>
		let currentPage = 1;
		const perPage = 20;

		const container = document.getElementById('cardsContainer');
		const msg = document.getElementById('msg');

		async function loadItems(filters = {}) {
			const url = new URL('api.php', window.location.href);

			filters.page = currentPage;
			filters.per_page = perPage;

			Object.keys(filters).forEach(k => {
				if (filters[k]) url.searchParams.append(k, filters[k]);
			});

			const res = await fetch(url);
			const data = await res.json();
			renderCards(data.items || []);
		}

		function renderCards(items) {
			container.innerHTML = '';

			if (items.length === 0) {
				container.innerHTML = '<p>No se encontraron resultados.</p>';
				return;
			}

			items.forEach(it => {
				const card = document.createElement('div');
				card.className = 'card m-3';
				card.style.width = '18rem';

				card.innerHTML = `
                <img src="${it.url}" class="card-img-top" alt="${it.name}">
                <div class="card-body">
                    <h5 class="card-title">${it.name}</h5>
                    <h6>${it.categoria}</h6>
                    <p class="card-text">${it.descrip}</p>
                    <a href="${it.link}" class="btn btn-primary" target="_blank">Ver Modelos</a>
                </div>
            `;
				container.appendChild(card);
			});
		}

		document.getElementById('filterForm').addEventListener('submit', e => {
			e.preventDefault();
			currentPage = 1;
			loadItems({
				nombre: nombreInput.value.trim(),
				categoria: categoriaSelect.value
			});
		});

		document.getElementById('prevBtn').addEventListener('click', () => {
			if (currentPage > 1) {
				currentPage--;
				loadItems();
			}
		});

		document.getElementById('nextBtn').addEventListener('click', () => {
			currentPage++;
			loadItems();
		});

		document.getElementById('addForm').addEventListener('submit', async e => {
			e.preventDefault();
			const payload = Object.fromEntries(new FormData(e.target));

			if (!payload.name || !payload.categoria || !payload.descrip) {
				Swal.fire({
					icon: 'error',
					title: 'Faltan campos obligatorios'
				});
				return;
			}

			const res = await fetch('api.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(payload)
			});

			const data = await res.json();

			if (data.success) {
				Swal.fire({
					icon: 'success',
					title: 'Ítem agregado'
				});
				e.target.reset();
				loadItems();
			}
		});

		document.getElementById('temaBtn').addEventListener('click', () => {
			const url = new URL(window.location.href);
			url.searchParams.set(
				'tema',
				url.searchParams.get('tema') === 'oscuro' ? 'claro' : 'oscuro'
			);
			window.location.href = url;
		});

		loadItems();
	</script>

</body>

</html>