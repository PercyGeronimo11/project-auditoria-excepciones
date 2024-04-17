<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informe de Ventas</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    }

    .container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
    text-align: center;
    color: #333;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    }

    table, th, td {
    border: 1px solid #ddd;
    }

    th, td {
    padding: 12px;
    text-align: left;
    }

    thead {
    background-color: #f2f2f2;
    }

    tbody tr:nth-child(even) {
    background-color: #f9f9f9;
    }

    tbody tr:hover {
    background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Informe de Ventas</h1>
    <table>
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Producto</th>
          <th>Cantidad Vendida</th>
          <th>Precio Unitario</th>
          <th>Total Ventas</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Juan Pérez</td>
          <td>Camiseta</td>
          <td>20</td>
          <td>$15.00</td>
          <td>$300.00</td>
        </tr>
        <tr>
          <td>María García</td>
          <td>Pantalón</td>
          <td>15</td>
          <td>$25.00</td>
          <td>$375.00</td>
        </tr>
        <tr>
          <td>Carlos López</td>
          <td>Zapatos</td>
          <td>10</td>
          <td>$40.00</td>
          <td>$400.00</td>
        </tr>
        <tr>
          <td>Ana Martínez</td>
          <td>Bufanda</td>
          <td>30</td>
          <td>$10.00</td>
          <td>$300.00</td>
        </tr>
        <tr>
          <td>Luis Rodríguez</td>
          <td>Chaqueta</td>
          <td>5</td>
          <td>$50.00</td>
          <td>$250.00</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
