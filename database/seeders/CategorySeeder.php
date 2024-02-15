<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::query()->truncate();

        DB::insert("INSERT INTO `categories` (`id`,`category_name`,`slug`, `description`, `parent_category`, `category_image_one`,`category_image_two`, `category_image_three`, `category_video`,`precedence`) VALUES
            (1,'Women','women',null,0,null,null,null,null,1),
            (2,'Men','men',null,0,null,null,null,null,2),
            (3,'Kids','kids',null,0,null,null,null,null,3),
            (4,'Home Furnishings','home-furnishings',null,0,null,null,null,null,4),
            (5,'Beauty','beauty',null,0,null,null,null,null,5),
            (6,'Accessories','accessories',null,0,null,null,null,null,6),

            (7,'Saree','saree',null,1,null,null,null,null,1),
            (8,'Salwar Kameez','salwar-kameez',null,1,null,null,null,null,2),
            (9,'Kurti & Fatua','kurti-fatua',null,1,null,null,null,null,3),
            (10,'Tops & Shirts','tops-shirts',null,1,null,null,null,null,4),
            (11,'Kimono','kimono',null,1,null,null,null,null,5),
            (12,'Kaftan','kaftan',null,1,null,null,null,null,6),

            (13,'Panjabi','panjabi',null,2,null,null,null,null,1),
            (14,'Vest','vest',null,2,null,null,null,null,2),
            (15,'T-Shirt','t-shirt',null,2,null,null,null,null,3),
            (16,'Fatua','fatua',null,2,null,null,null,null,4),
            (17,'Shirts','shirts',null,2,null,null,null,null,5),
            (18,'Jackets','jackets',null,2,null,null,null,null,6),

            (19,'Baby Kantha','baby-kantha',null,4,null,null,null,null,1),
            (20,'Cushion Cover','cushion-cover',null,4,null,null,null,null,2),
            (21,'Ceramic','ceramic',null,4,null,null,null,null,3),
            (22,'Table Cloth','table-cloth',null,4,null,null,null,null,4),
            (23,'Bed Cover','bed-cover',null,4,null,null,null,null,5),
            (24,'Basket','basket',null,4,null,null,null,null,6),
            (25,'Napkin','napkin',null,4,null,null,null,null,7),
            (26,'Table Runner','table-runner',null,4,null,null,null,null,8),

            (27,'Aatong','aatong',null,6,null,null,null,null,1),
            (28,'Cangbuk','cangbuk',null,6,null,null,null,null,2),
            (29,'Ashtodhatu Jewellery','ashtodhatu-jewellery',null,6,null,null,null,null,3),
            (30,'Silver Jewellery','silver-jewellery',null,6,null,null,null,null,4),
            (31,'Metal Jewellery','metal-jewellery',null,6,null,null,null,null,5),
            (32,'Other Jewellery','other-jewellery',null,6,null,null,null,null,6),
            (33,'Scarves','scarves',null,6,null,null,null,null,7),
            (34,'Orna','orna',null,6,null,null,null,null,8),
            (35,'Stoles','stoles',null,6,null,null,null,null,9),
            (36,'Shawls','shawls',null,6,null,null,null,null,10),

            (37,'Jewellery','jewellery',null,0,null,null,null,null,7),

            (38,'Girls','girls',null,3,null,null,null,null,1),
            (39,'Boys','boys',null,3,null,null,null,null,2),
            (40,'Babies','babies',null,3,null,null,null,null,3),
            (41,'Toddlers','toddlers',null,3,null,null,null,null,4)
        ");
    }
}
