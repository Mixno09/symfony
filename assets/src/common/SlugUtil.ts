const cyrillicMap = new Map([
    ['а', 'a'],
    ['б', 'b'],
    ['в', 'v'],
    ['г', 'g'],
    ['д', 'd'],
    ['е', 'e'],
    ['ё', 'e'],
    ['ж', 'j'],
    ['з', 'z'],
    ['и', 'i'],
    ['й', 'i'],
    ['к', 'k'],
    ['л', 'l'],
    ['м', 'm'],
    ['н', 'n'],
    ['о', 'o'],
    ['п', 'p'],
    ['р', 'r'],
    ['с', 's'],
    ['т', 't'],
    ['у', 'u'],
    ['ф', 'f'],
    ['х', 'ch'],
    ['ц', 'c'],
    ['ч', 'ch'],
    ['ш', 'sh'],
    ['щ', 'sc'],
    ['ы', 'y'],
    ['э', 'e'],
    ['ю', 'iu'],
    ['я', 'ia'],
]);

export class SlugUtil {
    makeSlug(value: string): string {
        return value
            .toLocaleLowerCase()
            .replace(/\s/g, '-')
            .replace(
                /[^a-z0-9-]/g,
                (char) => {
                    if (! cyrillicMap.has(char)) {
                        return char;
                    }

                    let value = cyrillicMap.get(char);
                    if (value === undefined) {
                        value = "";
                    }
                    return value;
                }
            )
            .replace(/[^a-z0-9-]/g, '')
            .replace(/--+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }
}

export default new SlugUtil();
