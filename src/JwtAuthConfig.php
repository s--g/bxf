<?php declare(strict_types = 1);

namespace BxF;

/**
 * @method string getName()
 * @method $this setName(string $value)
 *
 * @method string getSigningPrivateKey()
 * @method $this setSigningPrivateKey(string $value)
 *
 * @method string getSigningPublicKey()
 * @method $this setSigningPublicKey(string $value)
 */
class JwtAuthConfig
{
    use PropertyAccess;
    
    protected string $name;
    protected string $signingPrivateKey;
    protected string $signingPublicKey;
}