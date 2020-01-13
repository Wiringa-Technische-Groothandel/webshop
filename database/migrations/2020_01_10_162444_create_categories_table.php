<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use WTG\Catalog\Api\Model\CategoryInterface;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\Model\Category;
use WTG\Foundation\Api\ErpModelInterface;

/**
 * Migration to create a new categories table.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'categories',
            function (Blueprint $table) {
                $table->bigIncrements(CategoryInterface::FIELD_ID);
                $table->unsignedBigInteger(CategoryInterface::FIELD_PARENT_ID)->nullable();
                $table->foreign(CategoryInterface::FIELD_PARENT_ID)->references(CategoryInterface::FIELD_ID)->on(
                    'categories'
                );
                $table->string(ErpModelInterface::FIELD_ERP_ID);
                $table->string(CategoryInterface::FIELD_NAME);
                $table->string(CategoryInterface::FIELD_CODE);
                $table->integer(CategoryInterface::FIELD_LEVEL);
                $table->boolean(CategoryInterface::FIELD_SHOW_IN_MENU);
                $table->timestamps();
                $table->timestamp(ErpModelInterface::FIELD_SYNCHRONIZED_AT)->nullable();
            }
        );

        $this->createRootCategory();

        Schema::table(
            'products',
            function (Blueprint $table) {
                $table->unsignedBigInteger(ProductInterface::FIELD_CATEGORY_ID)->default(
                    Category::DEFAULT_ID
                )->after(ErpModelInterface::FIELD_ERP_ID);
                $table->foreign(ProductInterface::FIELD_CATEGORY_ID)->references(CategoryInterface::FIELD_ID)->on(
                    'categories'
                );
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'products',
            function (Blueprint $table) {
//                $table->dropForeign([ ProductInterface::FIELD_CATEGORY_ID ]);
                $table->dropColumn(ProductInterface::FIELD_CATEGORY_ID);
            }
        );

        Schema::dropIfExists('categories');
    }

    /**
     * @return Category
     */
    protected function createRootCategory(): Category
    {
        Category::unguard();

        /** @var Category $category */
        $category = Category::create(
            [
                ErpModelInterface::FIELD_ERP_ID => Category::ROOT_ERP_ID,
                CategoryInterface::FIELD_NAME => 'Root',
                CategoryInterface::FIELD_CODE => '',
                CategoryInterface::FIELD_LEVEL => 0,
                CategoryInterface::FIELD_SHOW_IN_MENU => false,
            ]
        );

        Category::reguard();

        DB::statement(
            'UPDATE categories SET id = ? WHERE id = ?',
            [
                Category::DEFAULT_ID,
                $category->getId(),
            ]
        );

        return $category;
    }
}
