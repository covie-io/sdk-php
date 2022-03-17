<?php

namespace Covie\SDK\Model;

final class DocumentType
{
    public const INSURANCE_CARD = 'insurance_card';
    public const DECLARATION = 'declaration';
    public const EOI = 'evidence_of_insurance';
    public const CANCELLATION = 'cancellation';
    public const TYPES = [self::INSURANCE_CARD, self::DECLARATION, self::EOI];
}