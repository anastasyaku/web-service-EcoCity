<?php

namespace App;

class RecyclableMarkClasses
{
    private const MARK_CLASSES = [
        'event' => EventMark::class,
        'trash' => TrashMark::class,
        'cloth' => RecyclableMark::class,
        'glass' => RecyclableMark::class,
        'plastic' => RecyclableMark::class,
        'paper' => RecyclableMark::class,
        'metall' => RecyclableMark::class,
        'tehn' => RecyclableMark::class,
        'akk' => RecyclableMark::class,
        'lamp' => RecyclableMark::class,
    ];

    static function getClassForType(string $type): string
    {
        return self::MARK_CLASSES[$type];
    }
}
