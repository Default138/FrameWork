<style>
    .brand-dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0; /* Remove o espaçamento entre o link e o menu */
    }
</style>
<html>
    <head>
        <title>Cadastro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="dropdown brand-dropdown">
        <a class="navbar-brand dropdown-toggle" href="#" role="button"
        id="dropdownBrand" data-bs-toggle="dropdown" aria-expanded="false">
            Cadastro
        </a>
        <ul class="dropdown-menu" arial-labelledby="dropdownBrand">
            <li><a class="dropdown-menu" href="#">Link1</a></li>
            <li><a class="dropdown-menu" href="#">Link2</a></li>
            <li><a class="dropdown-menu" href="#">Link3</a></li>
        </ul>
    </div>
    </body>
</html>