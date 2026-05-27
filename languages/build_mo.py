import struct, re, os

def parse_po(filepath):
    entries = []
    with open(filepath, encoding='utf-8') as f:
        content = f.read()
    blocks = re.split(r'\n(?=msgid)', content)
    pat_id  = re.compile(r'^msgid\s+"((?:[^"\\]|\\.)*)"', re.MULTILINE)
    pat_str = re.compile(r'^msgstr\s+"((?:[^"\\]|\\.)*)"', re.MULTILINE)
    for block in blocks:
        m_id  = pat_id.search(block)
        m_str = pat_str.search(block)
        if not m_id or not m_str:
            continue
        msgid  = m_id.group(1).encode('raw_unicode_escape').decode('unicode_escape')
        msgstr = m_str.group(1).encode('raw_unicode_escape').decode('unicode_escape')
        if msgstr:
            entries.append((msgid, msgstr))
    return entries

def write_mo(entries, filepath):
    entries = sorted(entries, key=lambda x: x[0])
    N = len(entries)
    orig_off  = 28
    trans_off = orig_off  + N * 8
    keys_off  = trans_off + N * 8
    orig_enc  = [k.encode('utf-8') for k, v in entries]
    trans_enc = [v.encode('utf-8') for k, v in entries]
    orig_data  = b''
    trans_data = b''
    orig_positions  = []
    trans_positions = []
    cur = keys_off
    for s in orig_enc:
        orig_positions.append((len(s), cur))
        orig_data += s + b'\x00'
        cur += len(s) + 1
    for s in trans_enc:
        trans_positions.append((len(s), cur))
        trans_data += s + b'\x00'
        cur += len(s) + 1
    mo = struct.pack('<IIIIIII',
        0x950412de, 0, N,
        orig_off, trans_off, 0, keys_off + len(orig_data) + len(trans_data))
    for length, offset in orig_positions:
        mo += struct.pack('<II', length, offset)
    for length, offset in trans_positions:
        mo += struct.pack('<II', length, offset)
    mo += orig_data + trans_data
    with open(filepath, 'wb') as f:
        f.write(mo)
    print(f'  {os.path.basename(filepath)}: {len(entries)} strings, {len(mo)} bytes')

lang = os.path.dirname(os.path.abspath(__file__))
for locale in ['en_US', 'es_ES']:
    po = os.path.join(lang, f'groq-ai-connector-{locale}.po')
    mo = os.path.join(lang, f'groq-ai-connector-{locale}.mo')
    entries = parse_po(po)
    write_mo(entries, mo)
print('Done.')
