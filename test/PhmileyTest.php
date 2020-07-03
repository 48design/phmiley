<?php
namespace FortyeightDesign\Phmiley\Test;

use FortyeightDesign\Phmiley\Phmiley;

class PhmileyTest extends \PHPUnit\Framework\TestCase
{
    // https://unicode.org/emoji/charts/full-emoji-list.html
    // Array.from(document.querySelectorAll('[class="code"]')).map((codes) => {
    //     return codes.textContent.replace(/\s?U\+([0-9a-f]+)/ig, "&#x$1;")
    //   }).join("")
    // and added one with a hair modifier: 🏃🏻‍♀️
    static $allEmojis = "😀😃😄😁😆😅🤣😂🙂🙃😉😊😇🥰😍🤩😘😗☺😚😙🥲😋😛😜🤪😝🤑🤗🤭🤫🤔🤐🤨😐😑😶😏😒🙄😬🤥😌😔😪🤤😴😷🤒🤕🤢🤮🤧🥵🥶🥴😵🤯🤠🥳🥸😎🤓🧐😕😟🙁☹😮😯😲😳🥺😦😧😨😰😥😢😭😱😖😣😞😓😩😫🥱😤😡😠🤬😈👿💀☠💩🤡👹👺👻👽👾🤖😺😸😹😻😼😽🙀😿😾🙈🙉🙊💋💌💘💝💖💗💓💞💕💟❣💔❤🧡💛💚💙💜🤎🖤🤍💯💢💥💫💦💨🕳💣💬👁️‍🗨️🗨🗯💭💤👋🤚🖐✋🖖👌🤌🤏✌🤞🤟🤘🤙👈👉👆🖕👇☝👍👎✊👊🤛🤜👏🙌👐🤲🤝🙏✍💅🤳💪🦾🦿🦵🦶👂🦻👃🧠🫀🫁🦷🦴👀👁👅👄👶🧒👦👧🧑👱👨🧔👨‍🦰👨‍🦱👨‍🦳👨‍🦲👩👩‍🦰🧑‍🦰👩‍🦱🧑‍🦱👩‍🦳🧑‍🦳👩‍🦲🧑‍🦲👱‍♀️👱‍♂️🧓👴👵🙍🙍‍♂️🙍‍♀️🙎🙎‍♂️🙎‍♀️🙅🙅‍♂️🙅‍♀️🙆🙆‍♂️🙆‍♀️💁💁‍♂️💁‍♀️🙋🙋‍♂️🙋‍♀️🧏🧏‍♂️🧏‍♀️🙇🙇‍♂️🙇‍♀️🤦🤦‍♂️🤦‍♀️🤷🤷‍♂️🤷‍♀️🧑‍⚕️👨‍⚕️👩‍⚕️🧑‍🎓👨‍🎓👩‍🎓🧑‍🏫👨‍🏫👩‍🏫🧑‍⚖️👨‍⚖️👩‍⚖️🧑‍🌾👨‍🌾👩‍🌾🧑‍🍳👨‍🍳👩‍🍳🧑‍🔧👨‍🔧👩‍🔧🧑‍🏭👨‍🏭👩‍🏭🧑‍💼👨‍💼👩‍💼🧑‍🔬👨‍🔬👩‍🔬🧑‍💻👨‍💻👩‍💻🧑‍🎤👨‍🎤👩‍🎤🧑‍🎨👨‍🎨👩‍🎨🧑‍✈️👨‍✈️👩‍✈️🧑‍🚀👨‍🚀👩‍🚀🧑‍🚒👨‍🚒👩‍🚒👮👮‍♂️👮‍♀️🕵🕵️‍♂️🕵️‍♀️💂💂‍♂️💂‍♀️🥷👷👷‍♂️👷‍♀️🤴👸👳👳‍♂️👳‍♀️👲🧕🤵🤵‍♂️🤵‍♀️👰👰‍♂️👰‍♀️🤰🤱👩‍🍼👨‍🍼🧑‍🍼👼🎅🤶🧑‍🎄🦸🦸‍♂️🦸‍♀️🦹🦹‍♂️🦹‍♀️🧙🧙‍♂️🧙‍♀️🧚🧚‍♂️🧚‍♀️🧛🧛‍♂️🧛‍♀️🧜🧜‍♂️🧜‍♀️🧝🧝‍♂️🧝‍♀️🧞🧞‍♂️🧞‍♀️🧟🧟‍♂️🧟‍♀️💆💆‍♂️💆‍♀️💇💇‍♂️💇‍♀️🚶🚶‍♂️🚶‍♀️🧍🧍‍♂️🧍‍♀️🧎🧎‍♂️🧎‍♀️🧑‍🦯👨‍🦯👩‍🦯🧑‍🦼👨‍🦼👩‍🦼🧑‍🦽👨‍🦽👩‍🦽🏃🏃‍♂️🏃‍♀️💃🕺🕴👯👯‍♂️👯‍♀️🧖🧖‍♂️🧖‍♀️🧗🧗‍♂️🧗‍♀️🤺🏇⛷🏂🏌🏌️‍♂️🏌️‍♀️🏄🏄‍♂️🏄‍♀️🚣🚣‍♂️🚣‍♀️🏊🏊‍♂️🏊‍♀️⛹⛹️‍♂️⛹️‍♀️🏋🏋️‍♂️🏋️‍♀️🚴🚴‍♂️🚴‍♀️🚵🚵‍♂️🚵‍♀️🤸🤸‍♂️🤸‍♀️🤼🤼‍♂️🤼‍♀️🤽🤽‍♂️🤽‍♀️🤾🤾‍♂️🤾‍♀️🤹🤹‍♂️🤹‍♀️🧘🧘‍♂️🧘‍♀️🛀🛌🧑‍🤝‍🧑👭👫👬💏👩‍❤️‍💋‍👨👨‍❤️‍💋‍👨👩‍❤️‍💋‍👩💑👩‍❤️‍👨👨‍❤️‍👨👩‍❤️‍👩👪👨‍👩‍👦👨‍👩‍👧👨‍👩‍👧‍👦👨‍👩‍👦‍👦👨‍👩‍👧‍👧👨‍👨‍👦👨‍👨‍👧👨‍👨‍👧‍👦👨‍👨‍👦‍👦👨‍👨‍👧‍👧👩‍👩‍👦👩‍👩‍👧👩‍👩‍👧‍👦👩‍👩‍👦‍👦👩‍👩‍👧‍👧👨‍👦👨‍👦‍👦👨‍👧👨‍👧‍👦👨‍👧‍👧👩‍👦👩‍👦‍👦👩‍👧👩‍👧‍👦👩‍👧‍👧🗣👤👥🫂👣🦰🦱🦳🦲🐵🐒🦍🦧🐶🐕🦮🐕‍🦺🐩🐺🦊🦝🐱🐈🐈‍⬛🦁🐯🐅🐆🐴🐎🦄🦓🦌🦬🐮🐂🐃🐄🐷🐖🐗🐽🐏🐑🐐🐪🐫🦙🦒🐘🦣🦏🦛🐭🐁🐀🐹🐰🐇🐿🦫🦔🦇🐻🐻‍❄️🐨🐼🦥🦦🦨🦘🦡🐾🦃🐔🐓🐣🐤🐥🐦🐧🕊🦅🦆🦢🦉🦤🪶🦩🦚🦜🐸🐊🐢🦎🐍🐲🐉🦕🦖🐳🐋🐬🦭🐟🐠🐡🦈🐙🐚🐌🦋🐛🐜🐝🪲🐞🦗🪳🕷🕸🦂🦟🪰🪱🦠💐🌸💮🏵🌹🥀🌺🌻🌼🌷🌱🪴🌲🌳🌴🌵🌾🌿☘🍀🍁🍂🍃🍇🍈🍉🍊🍋🍌🍍🥭🍎🍏🍐🍑🍒🍓🫐🥝🍅🫒🥥🥑🍆🥔🥕🌽🌶🫑🥒🥬🥦🧄🧅🍄🥜🌰🍞🥐🥖🫓🥨🥯🥞🧇🧀🍖🍗🥩🥓🍔🍟🍕🌭🥪🌮🌯🫔🥙🧆🥚🍳🥘🍲🫕🥣🥗🍿🧈🧂🥫🍱🍘🍙🍚🍛🍜🍝🍠🍢🍣🍤🍥🥮🍡🥟🥠🥡🦀🦞🦐🦑🦪🍦🍧🍨🍩🍪🎂🍰🧁🥧🍫🍬🍭🍮🍯🍼🥛☕🫖🍵🍶🍾🍷🍸🍹🍺🍻🥂🥃🥤🧋🧃🧉🧊🥢🍽🍴🥄🔪🏺🌍🌎🌏🌐🗺🗾🧭🏔⛰🌋🗻🏕🏖🏜🏝🏞🏟🏛🏗🧱🪨🪵🛖🏘🏚🏠🏡🏢🏣🏤🏥🏦🏨🏩🏪🏫🏬🏭🏯🏰💒🗼🗽⛪🕌🛕🕍⛩🕋⛲⛺🌁🌃🏙🌄🌅🌆🌇🌉♨🎠🎡🎢💈🎪🚂🚃🚄🚅🚆🚇🚈🚉🚊🚝🚞🚋🚌🚍🚎🚐🚑🚒🚓🚔🚕🚖🚗🚘🚙🛻🚚🚛🚜🏎🏍🛵🦽🦼🛺🚲🛴🛹🛼🚏🛣🛤🛢⛽🚨🚥🚦🛑🚧⚓⛵🛶🚤🛳⛴🛥🚢✈🛩🛫🛬🪂💺🚁🚟🚠🚡🛰🚀🛸🛎🧳⌛⏳⌚⏰⏱⏲🕰🕛🕧🕐🕜🕑🕝🕒🕞🕓🕟🕔🕠🕕🕡🕖🕢🕗🕣🕘🕤🕙🕥🕚🕦🌑🌒🌓🌔🌕🌖🌗🌘🌙🌚🌛🌜🌡☀🌝🌞🪐⭐🌟🌠🌌☁⛅⛈🌤🌥🌦🌧🌨🌩🌪🌫🌬🌀🌈🌂☂☔⛱⚡❄☃⛄☄🔥💧🌊🎃🎄🎆🎇🧨✨🎈🎉🎊🎋🎍🎎🎏🎐🎑🧧🎀🎁🎗🎟🎫🎖🏆🏅🥇🥈🥉⚽⚾🥎🏀🏐🏈🏉🎾🥏🎳🏏🏑🏒🥍🏓🏸🥊🥋🥅⛳⛸🎣🤿🎽🎿🛷🥌🎯🪀🪁🎱🔮🪄🧿🎮🕹🎰🎲🧩🧸🪅🪆♠♥♦♣♟🃏🀄🎴🎭🖼🎨🧵🪡🧶🪢👓🕶🥽🥼🦺👔👕👖🧣🧤🧥🧦👗👘🥻🩱🩲🩳👙👚👛👜👝🛍🎒🩴👞👟🥾🥿👠👡🩰👢👑👒🎩🎓🧢🪖⛑📿💄💍💎🔇🔈🔉🔊📢📣📯🔔🔕🎼🎵🎶🎙🎚🎛🎤🎧📻🎷🪗🎸🎹🎺🎻🪕🥁🪘📱📲☎📞📟📠🔋🔌💻🖥🖨⌨🖱🖲💽💾💿📀🧮🎥🎞📽🎬📺📷📸📹📼🔍🔎🕯💡🔦🏮🪔📔📕📖📗📘📙📚📓📒📃📜📄📰🗞📑🔖🏷💰🪙💴💵💶💷💸💳🧾💹✉📧📨📩📤📥📦📫📪📬📭📮🗳✏✒🖋🖊🖌🖍📝💼📁📂🗂📅📆🗒🗓📇📈📉📊📋📌📍📎🖇📏📐✂🗃🗄🗑🔒🔓🔏🔐🔑🗝🔨🪓⛏⚒🛠🗡⚔🔫🪃🏹🛡🪚🔧🪛🔩⚙🗜⚖🦯🔗⛓🪝🧰🧲🪜⚗🧪🧫🧬🔬🔭📡💉🩸💊🩹🩺🚪🛗🪞🪟🛏🛋🪑🚽🪠🚿🛁🪤🪒🧴🧷🧹🧺🧻🪣🧼🪥🧽🧯🛒🚬⚰🪦⚱🗿🪧🏧🚮🚰♿🚹🚺🚻🚼🚾🛂🛃🛄🛅⚠🚸⛔🚫🚳🚭🚯🚱🚷📵🔞☢☣⬆↗➡↘⬇↙⬅↖↕↔↩↪⤴⤵🔃🔄🔙🔚🔛🔜🔝🛐⚛🕉✡☸☯✝☦☪☮🕎🔯♈♉♊♋♌♍♎♏♐♑♒♓⛎🔀🔁🔂▶⏩⏭⏯◀⏪⏮🔼⏫🔽⏬⏸⏹⏺⏏🎦🔅🔆📶📳📴♀♂⚧✖➕➖➗♾‼⁉❓❔❕❗〰💱💲⚕♻⚜🔱📛🔰⭕✅☑✔❌❎➰➿〽✳✴❇©®™#️⃣*️⃣0️⃣1️⃣2️⃣3️⃣4️⃣5️⃣6️⃣7️⃣8️⃣9️⃣🔟🔠🔡🔢🔣🔤🅰🆎🅱🆑🆒🆓ℹ🆔Ⓜ🆕🆖🅾🆗🅿🆘🆙🆚🈁🈂🈷🈶🈯🉐🈹🈚🈲🉑🈸🈴🈳㊗㊙🈺🈵🔴🟠🟡🟢🔵🟣🟤⚫⚪🟥🟧🟨🟩🟦🟪🟫⬛⬜◼◻◾◽▪▫🔶🔷🔸🔹🔺🔻💠🔘🔳🔲🏁🚩🎌🏴🏳🏳️‍🌈🏳️‍⚧️🏴‍☠️🇦🇨🇦🇩🇦🇪🇦🇫🇦🇬🇦🇮🇦🇱🇦🇲🇦🇴🇦🇶🇦🇷🇦🇸🇦🇹🇦🇺🇦🇼🇦🇽🇦🇿🇧🇦🇧🇧🇧🇩🇧🇪🇧🇫🇧🇬🇧🇭🇧🇮🇧🇯🇧🇱🇧🇲🇧🇳🇧🇴🇧🇶🇧🇷🇧🇸🇧🇹🇧🇻🇧🇼🇧🇾🇧🇿🇨🇦🇨🇨🇨🇩🇨🇫🇨🇬🇨🇭🇨🇮🇨🇰🇨🇱🇨🇲🇨🇳🇨🇴🇨🇵🇨🇷🇨🇺🇨🇻🇨🇼🇨🇽🇨🇾🇨🇿🇩🇪🇩🇬🇩🇯🇩🇰🇩🇲🇩🇴🇩🇿🇪🇦🇪🇨🇪🇪🇪🇬🇪🇭🇪🇷🇪🇸🇪🇹🇪🇺🇫🇮🇫🇯🇫🇰🇫🇲🇫🇴🇫🇷🇬🇦🇬🇧🇬🇩🇬🇪🇬🇫🇬🇬🇬🇭🇬🇮🇬🇱🇬🇲🇬🇳🇬🇵🇬🇶🇬🇷🇬🇸🇬🇹🇬🇺🇬🇼🇬🇾🇭🇰🇭🇲🇭🇳🇭🇷🇭🇹🇭🇺🇮🇨🇮🇩🇮🇪🇮🇱🇮🇲🇮🇳🇮🇴🇮🇶🇮🇷🇮🇸🇮🇹🇯🇪🇯🇲🇯🇴🇯🇵🇰🇪🇰🇬🇰🇭🇰🇮🇰🇲🇰🇳🇰🇵🇰🇷🇰🇼🇰🇾🇰🇿🇱🇦🇱🇧🇱🇨🇱🇮🇱🇰🇱🇷🇱🇸🇱🇹🇱🇺🇱🇻🇱🇾🇲🇦🇲🇨🇲🇩🇲🇪🇲🇫🇲🇬🇲🇭🇲🇰🇲🇱🇲🇲🇲🇳🇲🇴🇲🇵🇲🇶🇲🇷🇲🇸🇲🇹🇲🇺🇲🇻🇲🇼🇲🇽🇲🇾🇲🇿🇳🇦🇳🇨🇳🇪🇳🇫🇳🇬🇳🇮🇳🇱🇳🇴🇳🇵🇳🇷🇳🇺🇳🇿🇴🇲🇵🇦🇵🇪🇵🇫🇵🇬🇵🇭🇵🇰🇵🇱🇵🇲🇵🇳🇵🇷🇵🇸🇵🇹🇵🇼🇵🇾🇶🇦🇷🇪🇷🇴🇷🇸🇷🇺🇷🇼🇸🇦🇸🇧🇸🇨🇸🇩🇸🇪🇸🇬🇸🇭🇸🇮🇸🇯🇸🇰🇸🇱🇸🇲🇸🇳🇸🇴🇸🇷🇸🇸🇸🇹🇸🇻🇸🇽🇸🇾🇸🇿🇹🇦🇹🇨🇹🇩🇹🇫🇹🇬🇹🇭🇹🇯🇹🇰🇹🇱🇹🇲🇹🇳🇹🇴🇹🇷🇹🇹🇹🇻🇹🇼🇹🇿🇺🇦🇺🇬🇺🇲🇺🇳🇺🇸🇺🇾🇺🇿🇻🇦🇻🇨🇻🇪🇻🇬🇻🇮🇻🇳🇻🇺🇼🇫🇼🇸🇽🇰🇾🇪🇾🇹🇿🇦🇿🇲🇿🇼🏴󠁧󠁢󠁥󠁮󠁧󠁿🏴󠁧󠁢󠁳󠁣󠁴󠁿🏴󠁧󠁢󠁷󠁬󠁳󠁿🏃🏻‍♀️";
    static $testString = 'I could eat 11️⃣ 🍕 right now! 🤤 🧎🏾‍♂️👩🏿🇪🇺';

    private static function getPhpUnitVersion() {
        return \PHPUnit\Runner\Version::id();
    }
    
    private static function getPrivateProperty(&$object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
    
        return $property->getValue($object);
    }

    private static function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
    
        return $method->invokeArgs($object, $parameters);
    }

    private static function isLegacyVersion() {
        return version_compare(self::getPhpUnitVersion(), '9.1', '<');
    }


    public function getRegExFilename($Phmiley) {
        return $this->invokeMethod($Phmiley, 'getRegExFilename');
    }

    public function getData($Phmiley, $url) {
        return $this->invokeMethod($Phmiley, 'getData', [$url]);
    }

    public function getRegExDataDir($Phmiley) {
        return $this->getPrivateProperty($Phmiley, 'regExDataDir');
    }

    public function testParseEmojisAreAllReplaced()
    {
        $Phmiley = new Phmiley();
        $Phmiley->tagGenerator = function() {
            return '';        
        };

        $leftovers = preg_replace("/(?!^)/u", "\u{FE0F}", $Phmiley->parse(self::$allEmojis));
        $leftovers = $Phmiley->parse($leftovers);

        $this->assertEmpty($leftovers);
    }

    public function testParseEmojisWithoutVariantSelectorAreLeftOver()
    {
        $Phmiley = new Phmiley();
        $Phmiley->tagGenerator = function() {
            return '';        
        };

        $expectedLeftovers = '☺☹☠❣❤🕳🗨🗯🖐✌☝✍👁🕵🕴⛷🏌⛹🏋🗣🐿🕊🕷🕸🏵☘🌶🍽🗺🏔⛰🏕🏖🏜🏝🏞🏟🏛🏗🏘🏚⛩🏙♨🏎🏍🛣🛤🛢🛳⛴🛥✈🛩🛰🛎⏱⏲🕰🌡☀☁⛈🌤🌥🌦🌧🌨🌩🌪🌫🌬☂⛱❄☃☄🎗🎟🎖⛸🕹♠♥♦♣♟🖼🕶🛍⛑🎙🎚🎛☎🖥🖨⌨🖱🖲🎞📽🕯🗞🏷✉🗳✏✒🖋🖊🖌🖍🗂🗒🗓🖇✂🗃🗄🗑🗝⛏⚒🛠🗡⚔🛡⚙🗜⚖⛓⚗🛏🛋⚰⚱⚠☢☣⬆↗➡↘⬇↙⬅↖↕↔↩↪⤴⤵⚛🕉✡☸☯✝☦☪☮▶⏭⏯◀⏮⏸⏹⏺⏏♀♂⚧✖♾‼⁉〰⚕♻⚜☑✔〽✳✴❇©®™🅰🅱ℹⓂ🅾🅿🈂🈷㊗㊙◼◻▪▫🏳';

        $this->assertEquals($expectedLeftovers, $Phmiley->parse(self::$allEmojis));
        
    }

    public function testParseGeneratesRegExFile()
    {
        $Phmiley = new Phmiley();

        $regExFile = $this->getRegExFilename($Phmiley);

        unlink($regExFile);
        if (self::isLegacyVersion()) {
            $this->assertFileNotExists($regExFile);
        } else {
            $this->assertFileDoesNotExist($regExFile);
        }
        
        $Phmiley = new Phmiley();
        $Phmiley->parse('😀');

        $this->assertFileExists($regExFile);
    }

    public function testTagGeneratorDefaultOutput()
    {
        $Phmiley = new Phmiley();

        $expectedOutput = 'I could eat 1<img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/31-20e3.png" style="height:1em;width:auto;vertical-align:text-bottom;"> <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f355.png" style="height:1em;width:auto;vertical-align:text-bottom;"> right now! <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f924.png" style="height:1em;width:auto;vertical-align:text-bottom;"> <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f9ce-1f3fe-200d-2642-fe0f.png" style="height:1em;width:auto;vertical-align:text-bottom;"><img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f469-1f3ff.png" style="height:1em;width:auto;vertical-align:text-bottom;"><img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f1ea-1f1fa.png" style="height:1em;width:auto;vertical-align:text-bottom;">';

        $this->assertEquals($expectedOutput, $Phmiley->parse(self::$testString));
    }

    public function testGetDataWithWrongUrlThrowsException()
    {
        $Phmiley = new Phmiley();
        $invalidUrl = 'https://example.com/404';
        $this->expectException(\FortyeightDesign\Phmiley\Exception::class);
        if (!self::isLegacyVersion()) {
            $this->expectExceptionMessageMatches("/^Unable to get emoji data from " . preg_quote($invalidUrl, '/') . "$/");
        }

        $this->getData($Phmiley, $invalidUrl);        
    }

    public function testSetPresetTwemoji72Output()
    {
        $Phmiley = new Phmiley();

        $Phmiley->setPreset("twemoji_72");

        $expectedOutput = 'I could eat 1<img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/31-20e3.png" style="height:1em;width:auto;vertical-align:text-bottom;"> <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f355.png" style="height:1em;width:auto;vertical-align:text-bottom;"> right now! <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f924.png" style="height:1em;width:auto;vertical-align:text-bottom;"> <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f9ce-1f3fe-200d-2642-fe0f.png" style="height:1em;width:auto;vertical-align:text-bottom;"><img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f469-1f3ff.png" style="height:1em;width:auto;vertical-align:text-bottom;"><img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/1f1ea-1f1fa.png" style="height:1em;width:auto;vertical-align:text-bottom;">';

        $this->assertEquals($expectedOutput, $Phmiley->parse(self::$testString));
    }

    public function testSetPresetTwemojiSvgOutput()
    {
        $Phmiley = new Phmiley();

        $Phmiley->setPreset("twemoji_svg");

        $expectedOutput = 'I could eat 1<img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/svg/31-20e3.svg" style="height:1em;width:auto;vertical-align:text-bottom;"> <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/svg/1f355.svg" style="height:1em;width:auto;vertical-align:text-bottom;"> right now! <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/svg/1f924.svg" style="height:1em;width:auto;vertical-align:text-bottom;"> <img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/svg/1f9ce-1f3fe-200d-2642-fe0f.svg" style="height:1em;width:auto;vertical-align:text-bottom;"><img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/svg/1f469-1f3ff.svg" style="height:1em;width:auto;vertical-align:text-bottom;"><img src="https://gitcdn.xyz/repo/twitter/twemoji/master/assets/svg/1f1ea-1f1fa.svg" style="height:1em;width:auto;vertical-align:text-bottom;">';

        $this->assertEquals($expectedOutput, $Phmiley->parse(self::$testString));
    }

    public function testSetPresetOpenMojiSvgOutput()
    {
        $Phmiley = new Phmiley();

        $Phmiley->setPreset("openmoji_svg");

        $expectedOutput = 'I could eat 1<img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/svg/0031-FE0F-20E3.svg" style="height:1.3em;width:auto;vertical-align:text-bottom;"> <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/svg/1F355.svg" style="height:1.3em;width:auto;vertical-align:text-bottom;"> right now! <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/svg/1F924.svg" style="height:1.3em;width:auto;vertical-align:text-bottom;"> <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/svg/1F9CE-1F3FE-200D-2642-FE0F.svg" style="height:1.3em;width:auto;vertical-align:text-bottom;"><img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/svg/1F469-1F3FF.svg" style="height:1.3em;width:auto;vertical-align:text-bottom;"><img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/svg/1F1EA-1F1FA.svg" style="height:1.3em;width:auto;vertical-align:text-bottom;">';

        $this->assertEquals($expectedOutput, $Phmiley->parse(self::$testString));
    }

    public function testSetPresetOpenMoji72Output()
    {
        $Phmiley = new Phmiley();

        $Phmiley->setPreset("openmoji_72");

        $expectedOutput = 'I could eat 1<img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/72x72/0031-FE0F-20E3.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"> <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/72x72/1F355.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"> right now! <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/72x72/1F924.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"> <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/72x72/1F9CE-1F3FE-200D-2642-FE0F.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"><img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/72x72/1F469-1F3FF.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"><img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/72x72/1F1EA-1F1FA.png" style="height:1.3em;width:auto;vertical-align:text-bottom;">';

        $this->assertEquals($expectedOutput, $Phmiley->parse(self::$testString));
    }

    public function testSetPresetOpenMoji618Output()
    {
        $Phmiley = new Phmiley();

        $Phmiley->setPreset("openmoji_618");

        $expectedOutput = 'I could eat 1<img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/618x618/0031-FE0F-20E3.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"> <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/618x618/1F355.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"> right now! <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/618x618/1F924.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"> <img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/618x618/1F9CE-1F3FE-200D-2642-FE0F.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"><img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/618x618/1F469-1F3FF.png" style="height:1.3em;width:auto;vertical-align:text-bottom;"><img src="https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/618x618/1F1EA-1F1FA.png" style="height:1.3em;width:auto;vertical-align:text-bottom;">';

        $this->assertEquals($expectedOutput, $Phmiley->parse(self::$testString));
    }

    public function testSetPresetInvalidValueThrowsException()
    {
        $Phmiley = new Phmiley();

        $this->expectException(\FortyeightDesign\Phmiley\Exception::class);
        if (!self::isLegacyVersion()) {
            $this->expectExceptionMessageMatches("/^Invalid preset 'nonExistentPreset'$/");
        }

        $Phmiley->setPreset("nonExistentPreset");
    }
    
    public function testSetVersionCreatesRegExFile()
    {
        $testVersion = '12.0';

        $Phmiley = new Phmiley();
        $Phmiley->setVersion($testVersion);

        $regExFile = $this->getRegExFilename($Phmiley);

        if (self::isLegacyVersion()) {
            $this->assertTrue(strpos($regExFile, "-{$testVersion}.") !== false);
        } else {
            $this->assertStringContainsString("-{$testVersion}.", $regExFile);
        }

        $this->assertFileExists($regExFile);

        unlink($regExFile);
        if (self::isLegacyVersion()) {
            $this->assertFileNotExists($regExFile);            
        } else {
            $this->assertFileDoesNotExist($regExFile);            
        }
    }

    public function testTagGeneratorCanBeResetToDefault()
    {
        $Phmiley = new Phmiley();
        $Phmiley->tagGenerator = function() {
            return '';        
        };

        $Phmiley->parse('🤓');

        $this->assertEmpty($Phmiley->parse('🤓'));

        $Phmiley->tagGenerator = null;
        
        $this->assertNotEmpty($Phmiley->parse('🤓'));
    }

    public function testTagGeneratorNotCallableThrowsException()
    {
        $Phmiley = new Phmiley();
        $Phmiley->tagGenerator = 'noncallable';

        $this->expectException(\FortyeightDesign\Phmiley\Exception::class);
        if (!self::isLegacyVersion()) {
            $this->expectExceptionMessageMatches("/^tagGenerator is not a callable!$/");
        }

        $Phmiley->parse('🤓');
    }
}
?>