<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgriAgroquimicosInsumosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Debido a la cantidad de datos, voy a crear los más importantes de cada categoría
        // Puedes expandir este seeder agregando más productos según las necesidades

        $agroquimicos = array_merge(
            $this->getAcaricidas(),
            $this->getAdherentes(),
            $this->getFungicidas(),
            $this->getHerbicidas(),
            $this->getInsecticidas(),
            $this->getNutrientes(),
            $this->getReguladores()
        );

        foreach ($agroquimicos as $agroquimico) {
            DB::table('agri_agroquimicos_insumos')->insert(array_merge($agroquimico, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Se insertaron ' . count($agroquimicos) . ' agroquímicos');
    }

    private function getAcaricidas(): array
    {
        return [
            [
                'nombre_comercial' => 'Abamex 1.8% EC',
                'ingrediente_activo' => 'Abamectina',
                'tipo' => 'acaricida',
                'codigo' => 'AGRO-ACA-01',
                'concentracion' => '1.8% EC',
                'formulacion' => 'EC',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'F-0001',
                'categoria_toxicologica' => 'II',
                'descripcion' => 'Acaricida e insecticida de amplio espectro',
                'cultivos_objetivo' => 'Algodón, Papa, Tomate, Espárrago',
                'plagas_objetivo' => 'Ácaros, Minadores',
                'activo' => true,
            ],
            [
                'nombre_comercial' => 'Vertimec 1.8% EC',
                'ingrediente_activo' => 'Abamectina',
                'tipo' => 'acaricida',
                'codigo' => 'AGRO-ACA-02',
                'concentracion' => '1.8% EC',
                'formulacion' => 'EC',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'F-0002',
                'categoria_toxicologica' => 'II',
                'descripcion' => 'Acaricida e insecticida biológico',
                'cultivos_objetivo' => 'Diversos cultivos',
                'plagas_objetivo' => 'Ácaros, Minadores, Trips',
                'activo' => true,
            ],
        ];
    }

    private function getAdherentes(): array
    {
        return [
            [
                'nombre_comercial' => 'Agridex',
                'ingrediente_activo' => 'Aceite vegetal modificado',
                'tipo' => 'adherente',
                'codigo' => 'AGRO-ADH-01',
                'concentracion' => '100%',
                'formulacion' => 'EC',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => null,
                'categoria_toxicologica' => 'U',
                'descripcion' => 'Adherente y dispersante',
                'cultivos_objetivo' => 'Todos los cultivos',
                'plagas_objetivo' => 'N/A - Coadyuvante',
                'activo' => true,
            ],
            [
                'nombre_comercial' => 'Break Thru',
                'ingrediente_activo' => 'Poliéter silicona',
                'tipo' => 'adherente',
                'codigo' => 'AGRO-ADH-02',
                'concentracion' => '100%',
                'formulacion' => 'SL',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => null,
                'categoria_toxicologica' => 'U',
                'descripcion' => 'Surfactante penetrante',
                'cultivos_objetivo' => 'Todos los cultivos',
                'plagas_objetivo' => 'N/A - Coadyuvante',
                'activo' => true,
            ],
        ];
    }

    private function getFungicidas(): array
    {
        return [
            [
                'nombre_comercial' => 'Antracol 70% WP',
                'ingrediente_activo' => 'Propineb',
                'tipo' => 'fungicida',
                'codigo' => 'AGRO-FUN-01',
                'concentracion' => '70% WP',
                'formulacion' => 'WP',
                'presentacion' => 'kg',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'F-1001',
                'categoria_toxicologica' => 'III',
                'descripcion' => 'Fungicida de contacto',
                'cultivos_objetivo' => 'Papa, Tomate, Vid',
                'plagas_objetivo' => 'Rancha, Mildiu',
                'activo' => true,
            ],
            [
                'nombre_comercial' => 'Ridomil Gold MZ 68 WP',
                'ingrediente_activo' => 'Metalaxil + Mancozeb',
                'tipo' => 'fungicida',
                'codigo' => 'AGRO-FUN-02',
                'concentracion' => '68% WP',
                'formulacion' => 'WP',
                'presentacion' => 'kg',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'F-1002',
                'categoria_toxicologica' => 'III',
                'descripcion' => 'Fungicida sistémico y de contacto',
                'cultivos_objetivo' => 'Papa, Tomate, Cebolla',
                'plagas_objetivo' => 'Rancha, Mildiu',
                'activo' => true,
            ],
        ];
    }

    private function getHerbicidas(): array
    {
        return [
            [
                'nombre_comercial' => 'Glifosato 48% SL',
                'ingrediente_activo' => 'Glifosato',
                'tipo' => 'herbicida',
                'codigo' => 'AGRO-HER-01',
                'concentracion' => '48% SL',
                'formulacion' => 'SL',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'H-2001',
                'categoria_toxicologica' => 'III',
                'descripcion' => 'Herbicida sistémico no selectivo',
                'cultivos_objetivo' => 'No selectivo',
                'plagas_objetivo' => 'Malezas anuales y perennes',
                'activo' => true,
            ],
            [
                'nombre_comercial' => '2,4-D Amina 72% SL',
                'ingrediente_activo' => '2,4-D',
                'tipo' => 'herbicida',
                'codigo' => 'AGRO-HER-02',
                'concentracion' => '72% SL',
                'formulacion' => 'SL',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'H-2002',
                'categoria_toxicologica' => 'II',
                'descripcion' => 'Herbicida selectivo hormonal',
                'cultivos_objetivo' => 'Cereales, Pastos',
                'plagas_objetivo' => 'Malezas de hoja ancha',
                'activo' => true,
            ],
        ];
    }

    private function getInsecticidas(): array
    {
        return [
            [
                'nombre_comercial' => 'Karate Zeon 5% CS',
                'ingrediente_activo' => 'Lambda cyhalotrina',
                'tipo' => 'insecticida',
                'codigo' => 'AGRO-INS-01',
                'concentracion' => '5% CS',
                'formulacion' => 'CS',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'I-3001',
                'categoria_toxicologica' => 'II',
                'descripcion' => 'Insecticida piretroide',
                'cultivos_objetivo' => 'Diversos cultivos',
                'plagas_objetivo' => 'Lepidópteros, Coleópteros',
                'activo' => true,
            ],
            [
                'nombre_comercial' => 'Lorsban 48% EC',
                'ingrediente_activo' => 'Clorpirifos',
                'tipo' => 'insecticida',
                'codigo' => 'AGRO-INS-02',
                'concentracion' => '48% EC',
                'formulacion' => 'EC',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'I-3002',
                'categoria_toxicologica' => 'II',
                'descripcion' => 'Insecticida organofosforado',
                'cultivos_objetivo' => 'Diversos cultivos',
                'plagas_objetivo' => 'Insectos masticadores y chupadores',
                'activo' => true,
            ],
        ];
    }

    private function getNutrientes(): array
    {
        return [
            [
                'nombre_comercial' => 'Abonofol 20-20-20',
                'ingrediente_activo' => 'NPK + Microelementos',
                'tipo' => 'nutriente_foliar',
                'codigo' => 'AGRO-NUT-01',
                'concentracion' => '20-20-20',
                'formulacion' => 'SL',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => null,
                'categoria_toxicologica' => 'U',
                'descripcion' => 'Fertilizante foliar completo',
                'cultivos_objetivo' => 'Todos los cultivos',
                'plagas_objetivo' => 'N/A - Nutrición',
                'activo' => true,
            ],
            [
                'nombre_comercial' => 'Bayfolan Forte',
                'ingrediente_activo' => 'NPK + Microelementos',
                'tipo' => 'nutriente_foliar',
                'codigo' => 'AGRO-NUT-02',
                'concentracion' => '11-8-6',
                'formulacion' => 'SL',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => null,
                'categoria_toxicologica' => 'U',
                'descripcion' => 'Fertilizante foliar completo',
                'cultivos_objetivo' => 'Todos los cultivos',
                'plagas_objetivo' => 'N/A - Nutrición',
                'activo' => true,
            ],
        ];
    }

    private function getReguladores(): array
    {
        return [
            [
                'nombre_comercial' => 'Activol',
                'ingrediente_activo' => 'Ácido giberélico',
                'tipo' => 'regulador_crecimiento',
                'codigo' => 'AGRO-REG-01',
                'concentracion' => '10% GA3',
                'formulacion' => 'SL',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 0.10,
                'registro_senasa' => 'R-4001',
                'categoria_toxicologica' => 'U',
                'descripcion' => 'Fitorregulador de crecimiento',
                'cultivos_objetivo' => 'Vid, Cítricos',
                'plagas_objetivo' => 'N/A - Fisiología',
                'activo' => true,
            ],
            [
                'nombre_comercial' => 'Ethrel 48% SL',
                'ingrediente_activo' => 'Ethephon',
                'tipo' => 'regulador_crecimiento',
                'codigo' => 'AGRO-REG-02',
                'concentracion' => '48% SL',
                'formulacion' => 'SL',
                'presentacion' => 'litro',
                'cantidad_presentacion' => 1.00,
                'registro_senasa' => 'R-4002',
                'categoria_toxicologica' => 'III',
                'descripcion' => 'Regulador de maduración',
                'cultivos_objetivo' => 'Café, Tomate, Piña',
                'plagas_objetivo' => 'N/A - Fisiología',
                'activo' => true,
            ],
        ];
    }
}
