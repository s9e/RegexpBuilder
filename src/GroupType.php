<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

enum GroupType: string
{
    case Capture          = '';
    case NonCapture       = '?:';
    case NonCaptureAtomic = '?>';
    case NonCaptureReset  = '?|';
}