<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\Model;

/**
 * Writes any model as XML under the given root element, without namespaces, the way
 * the readers read it back (they match elements by local name). Used to turn a model
 * into a fake myDATA response; the request writers keep the namespaces myDATA requires.
 *
 * @extends XMLWriter<Model>
 */
final class ModelWriter extends XMLWriter
{
    public function __construct(private readonly string $root)
    {
        parent::__construct();
    }

    /**
     * The model under a root element named after its class, e.g. ResponseDoc as <ResponseDoc>.
     */
    public static function write(Model $model): string
    {
        return (new self(substr($model::class, (int) strrpos($model::class, '\\') + 1)))->asXml($model);
    }

    /**
     * @param  Model  $data
     */
    public function asXml(mixed $data): string
    {
        $this->buildModel($this->document, $this->root, $data);

        return Dom::save($this->document);
    }
}
