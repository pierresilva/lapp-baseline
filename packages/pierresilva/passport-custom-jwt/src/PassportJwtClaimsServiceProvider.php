<?php
namespace pierresilva\PassportJwtClaims;
use App;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer;

class PassportJwtClaimsServiceProvider extends PassportServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setupConfig();
        parent::boot();
    }

    /**
     * Make the authorization service instance.
     *
     * @return AuthorizationServer
     */
    public function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            $this->app->make(ClientRepository::class),
            $this->app->make(AccessTokenRepository::class), // pierresilva\PassportJwtClaims\AccessTokenRepository
            $this->app->make(ScopeRepository::class),
            'file://'.Passport::keyPath('oauth-private.key'),
            'file://'.Passport::keyPath('oauth-public.key')
        );
    }

    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../resources/config/passport-jwt-claims.php');
        $this->publishes([$source => config_path('passport-jwt-claims.php')]);
        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/passport-jwt-claims.php', 'passport-jwt-claims'
        );

    }
}
