<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //定期项目
        Schema::create('project', function(Blueprint $table)
        {
            $table->increments('id')->comment('项目表');
            $table->char('name',100)->comment('项目名称');
            $table->decimal('total_amount',20,2)->unsigned()->default(0)->comment('总金额');
            $table->decimal('guarantee_fund',20,2)->unsigned()->default(0)->comment('保证金');
            $table->decimal('invested_amount',20,2)->unsigned()->default(0)->comment('已投资');
            $table->integer('invest_time')->comment('投资期限');
            $table->integer('invest_days')->comment('融资周期');
            $table->smallInteger('refund_type')->comment('还款类型');
            $table->integer('type')->comment('项目类型');
            $table->integer('product_line')->comment('项目产品线');
            $table->decimal('profit_percentage')->comment('利率');
            $table->decimal('base_rate')->comment('基准利率');
            $table->decimal('after_rate')->comment('平台利率');
            $table->smallInteger('status')->comment('项目状态')->default(100);
            $table->tinyInteger('created_by')->comment('创建人');
            $table->timestamp('publish_at')->comment('发布时间')->default('0000-00-00 00:00:00');
            $table->date('end_at')->comment('项目完结日');
            $table->smallInteger('pledge')->comment('是否可质押项目,针对普付宝;1为可质押')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->tinyInteger('before_refund')->default(0)->comment('1-提前还款');
            $table->timestamp('full_at')->default('0000-00-00 00:00:00')->comment('满标时间')->index();
            $table->index(['status','type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
