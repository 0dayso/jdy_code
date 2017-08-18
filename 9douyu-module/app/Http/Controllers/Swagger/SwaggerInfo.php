<?php
/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host=SWAGGER_HOST,
 *     basePath="/",
 *     @SWG\Info(
 *         version="4.0.0",
 *         title="9douyu-module-app",
 *         description="接口状态码：https://docs.google.com/spreadsheets/d/1oCs2ljK7zSxeYjFWdyfkJMo3wTpAU9kuLQjKo92TDR4/edit#gid=1944532250"
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
 *     url=SWAGGER_HOST
 *   )
 * ),
  * @SWG\Tag(
 *   name="APP-Home",
 *   description="首页相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-BankCard",
 *   description="银行卡相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-User",
 *   description="用户相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Current",
 *   description="零钱计划相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Sms",
 *   description="用户验证码相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Project",
 *   description="定期项目相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Invest",
 *   description="投资相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Order",
 *   description="订单相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Password",
 *   description="密码相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Bonus",
 *   description="红包\加息券相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="APP-Config",
 *   description="服务端配置相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="PC-User",
 *   description="PC端用户相关接口",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Invest",
 *   description="投资相关接口,适用于PC\WAP端",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="Current",
 *   description="零钱计划相关接口 PC WAP",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 * @SWG\Tag(
 *   name="JDY-Api",
 *   description="九斗鱼对接",
 *   @SWG\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://swagger.io"
 *   )
 * ),
 */


/**
 * @SWG\SecurityScheme(
 *   securityDefinition="9douyu-modules-api",
 *   type="oauth2",
 *   authorizationUrl="/oauth/authorize",
 *   tokenUrl="/oauth/access_token",
 *   flow="application",
 *   scopes={
 *     "public": "public"
 *   }
 * )
 */

//flow' => ['implicit', 'password', 'application', 'accessCode']
?>
