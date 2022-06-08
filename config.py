import os
from turtle import end_poly

def styleIt():
    root_dir = './admin/static/css/'
    dirs_raw = os.listdir(root_dir)

    main = False

    all = list()

    for file in dirs_raw:
        if file == 'index.scss':
            main = file
            continue

        all.append(file)
        if file == '.DS_Store':
            continue
        subdir = os.listdir(root_dir + file + '/')
        endpoints = list()

        local = False
        with open(f"{root_dir}{main}", 'w', encoding='utf-8') as m_file:
            for item in subdir:
                if item == '_index.scss':
                    local = item
                    continue
                endpoints.append(item)

        if local:
            with open(f"{root_dir}{file}/{local}", 'w', encoding='utf-8') as l_file:
                for endpoint in endpoints:
                    endpoint = endpoint.replace('_', '')
                    endpoint = endpoint.replace('.scss', '')
                    l_file.writelines(f"@import './{endpoint}';\n")

    with open(f"{root_dir}{main}", 'w', encoding='utf-8') as file:
        functions_pos = all.index("functions")
        functions = all.pop(functions_pos)
        all.insert(0, functions)
        for one in all:
            file.writelines(f"@import './{one}/index';\n")

    print("Styles updated successfully")

styleIt()