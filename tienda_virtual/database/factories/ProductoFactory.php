<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Producto;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        $categorias = [
            'Polos' => [
                ['nombre' => 'Polo básico algodón', 'img' => 'https://loremflickr.com/320/240/tshirt?lock=1'],
                ['nombre' => 'Polo estampado urbano', 'img' => 'https://loremflickr.com/320/240/tshirt?lock=2'],
                ['nombre' => 'Polo manga larga', 'img' => 'https://loremflickr.com/320/240/tshirt?lock=3'],
            ],
            'Camisas' => [
                ['nombre' => 'Camisa formal blanca', 'img' => 'https://loremflickr.com/320/240/shirt?lock=4'],
                ['nombre' => 'Camisa cuadros rojos', 'img' => 'https://loremflickr.com/320/240/shirt?lock=5'],
                ['nombre' => 'Camisa denim azul', 'img' => 'https://loremflickr.com/320/240/shirt?lock=6'],
            ],
            'Pantalones' => [
                ['nombre' => 'Pantalón jean clásico', 'img' => 'https://loremflickr.com/320/240/jeans?lock=7'],
                ['nombre' => 'Pantalón jogger negro', 'img' => 'https://loremflickr.com/320/240/jeans?lock=8'],
                ['nombre' => 'Pantalón de vestir gris', 'img' => 'https://loremflickr.com/320/240/jeans?lock=9'],
            ],
            'Shorts' => [
                ['nombre' => 'Short deportivo azul', 'img' => 'https://loremflickr.com/320/240/shorts?lock=10'],
                ['nombre' => 'Short casual beige', 'img' => 'https://loremflickr.com/320/240/shorts?lock=11'],
            ],
            'Vestidos' => [
                ['nombre' => 'Vestido floral verano', 'img' => 'https://loremflickr.com/320/240/dress?lock=12'],
                ['nombre' => 'Vestido elegante negro', 'img' => 'https://loremflickr.com/320/240/dress?lock=13'],
            ],
            'Faldas' => [
                ['nombre' => 'Falda plisada rosa', 'img' => 'https://loremflickr.com/320/240/skirt?lock=14'],
                ['nombre' => 'Falda denim azul', 'img' => 'https://loremflickr.com/320/240/skirt?lock=15'],
            ],
            'Casacas' => [
                ['nombre' => 'Casaca bomber negra', 'img' => 'https://loremflickr.com/320/240/jacket?lock=16'],
                ['nombre' => 'Casaca jean celeste', 'img' => 'https://loremflickr.com/320/240/jacket?lock=17'],
            ],
            'Abrigos' => [
                ['nombre' => 'Abrigo largo camel', 'img' => 'https://loremflickr.com/320/240/coat?lock=18'],
                ['nombre' => 'Abrigo de lana gris', 'img' => 'https://loremflickr.com/320/240/coat?lock=19'],
            ],
            'Ropa Deportiva' => [
                ['nombre' => 'Buzo deportivo gris', 'img' => 'https://loremflickr.com/320/240/sportswear?lock=20'],
                ['nombre' => 'Leggins deportivos', 'img' => 'https://loremflickr.com/320/240/sportswear?lock=21'],
            ],
            'Accesorios' => [
                ['nombre' => 'Gorra negra', 'img' => 'https://loremflickr.com/320/240/cap?lock=22'],
                ['nombre' => 'Cinturón cuero marrón', 'img' => 'https://loremflickr.com/320/240/belt?lock=23'],
            ],
            'Zapatos' => [
                ['nombre' => 'Zapatillas urbanas blancas', 'img' => 'https://loremflickr.com/320/240/shoes?lock=24'],
                ['nombre' => 'Zapatos de vestir negros', 'img' => 'https://loremflickr.com/320/240/shoes?lock=25'],
            ],
        ];
        $categoria = $this->faker->randomElement(array_keys($categorias));
        $producto = $this->faker->randomElement($categorias[$categoria]);
        $descripciones = [
            'Polo básico algodón' => 'Polo de algodón suave, ideal para el día a día.',
            'Polo estampado urbano' => 'Polo con diseño moderno y juvenil.',
            'Polo manga larga' => 'Perfecto para media estación, cómodo y versátil.',
            'Camisa formal blanca' => 'Camisa elegante para ocasiones formales.',
            'Camisa cuadros rojos' => 'Estilo casual, ideal para salidas.',
            'Camisa denim azul' => 'Camisa de mezclilla resistente y a la moda.',
            'Pantalón jean clásico' => 'Jean azul clásico, nunca pasa de moda.',
            'Pantalón jogger negro' => 'Cómodo y moderno, para uso diario.',
            'Pantalón de vestir gris' => 'Perfecto para la oficina o eventos.',
            'Short deportivo azul' => 'Short ligero para entrenar o pasear.',
            'Short casual beige' => 'Fresco y cómodo para el verano.',
            'Vestido floral verano' => 'Vestido fresco con estampado floral.',
            'Vestido elegante negro' => 'Ideal para eventos nocturnos.',
            'Falda plisada rosa' => 'Falda femenina y elegante.',
            'Falda denim azul' => 'Casual y combinable con todo.',
            'Casaca bomber negra' => 'Casaca moderna y abrigadora.',
            'Casaca jean celeste' => 'Clásica y resistente.',
            'Abrigo largo camel' => 'Abrigo elegante para invierno.',
            'Abrigo de lana gris' => 'Abriga y combina con todo.',
            'Buzo deportivo gris' => 'Para entrenar o estar cómodo en casa.',
            'Leggins deportivos' => 'Flexibles y cómodos para deporte.',
            'Gorra negra' => 'Accesorio básico para el sol.',
            'Cinturón cuero marrón' => 'Elegante y resistente.',
            'Zapatillas urbanas blancas' => 'Comodidad y estilo para tu día.',
            'Zapatos de vestir negros' => 'Para ocasiones formales y elegantes.',
        ];
        $nombre = $producto['nombre'];
        return [
            'nombre' => $nombre,
            'descripcion' => $descripciones[$nombre] ?? 'Producto de alta calidad.',
            'precio' => $this->faker->randomFloat(2, 39, 99), // Precio menor a 100 soles
            'stock' => 20, // Stock fijo de 20 prendas
            'imagen' => $producto['img'],
            'categoria' => $categoria,
        ];
    }
}
