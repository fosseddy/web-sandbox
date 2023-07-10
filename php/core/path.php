<?php

namespace path;

// NOTE(art): BASE_DIR is defined by user
function from_base(...$path)
{
    return join(DIRECTORY_SEPARATOR, [BASE_DIR, ...$path]);
}
