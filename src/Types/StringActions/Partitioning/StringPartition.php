<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 16.9.2017
 * Time: 17:22
 */

namespace Zuffik\Structures\Types\StringActions\Partitioning;


use Zuffik\Structures\Types\Enum;

class StringPartition extends Enum
{
    const STR_PART_UNTIL_FIRST = UntilFirst::class;
    const STR_PART_FROM_LAST = FromLast::class;
    const STR_PART_ALL_BETWEEN = AllBetween::class;
    const STR_PART_UNTIL_LAST = UntilLast::class;
    const STR_PART_FROM_FIRST = FromFirst::class;
}