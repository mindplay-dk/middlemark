<?php

namespace mindplay\middlemark;

interface RendererInterface
{
    /**
     * @param View $view
     *
     * @return string rendered HTML
     */
    public function render(View $view);
}
