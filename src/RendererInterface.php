<?php

namespace mindplay\middlemark;

interface RendererInterface
{
    /**
     * @param Document $doc
     *
     * @return string rendered HTML
     */
    public function render(Document $doc);
}
