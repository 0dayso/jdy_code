<?php
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host=SWAGGER_HOST,
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="9douyu-core",
 *         description="",
 *         )
 *     )
 *     )
 * )
 */

/**
 * @SWG\Tag(
 *   name="filter",
 *   description="滤镜",
 *   @SWG\ExternalDocumentation(
 *     description="滤镜",
 *     url=SWAGGER_URL
 *   )
 * ),
 * @SWG\Tag(
 *   name="Event",
 *   description="核心事件相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * )
 * @SWG\Tag(
 *   name="Order",
 *   description="核心订单相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="BankCard",
 *   description="核心银行卡相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Project",
 *   description="项目相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="User",
 *   description="用户相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Refund",
 *   description="回款相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Current",
 *   description="零钱计划相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Common",
 *   description="公共接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 */


/**
 * @SWG\SecurityScheme(
 *   securityDefinition="io84-api",
 *   type="oauth2",
 *   authorizationUrl=SWAGGER_AUTHORIZE_URL,
 *   tokenUrl=SWAGGER_ACCESS_TOKEN_URL,
 *   flow="application",
 *   scopes={
 *     "public": "public"
 *   }
 * )
 */

//flow' => ['implicit', 'password', 'application', 'accessCode']
?>
