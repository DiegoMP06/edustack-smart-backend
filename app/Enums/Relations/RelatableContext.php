<?php

namespace App\Enums\Relations;

enum RelatableContext: string
{
    case PREREQUISITE = 'prerequisite';     // A es pre-requisito de B
    case EVALUATION = 'evaluation';         // A evalúa B (form → assignment)
    case RESOURCE = 'resource';             // A es recurso de apoyo de B
    case SUPPLEMENT = 'supplement';         // A complementa B
    case RELATED = 'related';               // Relación genérica
    case REFERENCE = 'reference';           // A referencia a B
    case EMBEDDED = 'embedded';             // A está embebido en B
    case SHOWCASE = 'showcase';             // Proyecto se muestra en evento/actividad
    case SUBMISSION = 'submission';         // Entrega asociada
    case ACTIVITY = 'activity';             // Actividad relacionada
    case ANNOUNCEMENT = 'announcement';     // Post anuncia evento/curso
    case DOCUMENTATION = 'documentation';   // Post documenta proyecto/curso
}
