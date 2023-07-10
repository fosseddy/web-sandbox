<?php

namespace Path;

function from_base(...$path)
{
    return join(DIRECTORY_SEPARATOR, [BASE_DIR, ...$path]);
}
