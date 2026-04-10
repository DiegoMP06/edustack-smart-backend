<?php

namespace App\Enums\Forms;

enum LogicActionType: string
{
    case SHOW_QUESTION = 'show_question';       // Mostrar una pregunta oculta
    case HIDE_QUESTION = 'hide_question';       // Ocultar una pregunta visible
    case REQUIRE_QUESTION = 'require_question'; // Hacer obligatoria una pregunta opcional
    case SKIP_QUESTION = 'skip_question';       // Marcar pregunta como omitida
    case JUMP_TO_SECTION = 'jump_to_section';   // Ir directamente a otra sección
    case END_FORM = 'end_form';                 // Terminar el formulario aquí
}
