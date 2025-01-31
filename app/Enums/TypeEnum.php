<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TypeEnum extends Enum
{
    const Partida = 'fixture';
    const Equipe = 'team';
    const Jogador = 'player';
    const Campeonato = 'season';
    const League = 'liga';
    const Time = 'squad';
    const Tecnico = 'coach';
    const Escalacao = 'lineup';
}
