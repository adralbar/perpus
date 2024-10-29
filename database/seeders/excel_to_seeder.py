import pandas as pd

# Baca file Excel
file_path = 'path_to_your_excel_file.xlsx'  # Ganti dengan path file Excel Anda
df = pd.read_excel(file_path)

# Looping melalui setiap baris dan mengonversi ke format PHP array
for index, row in df.iterrows():
    php_array = f"""
    {{
        'npk_sistem' => '{row['NPK SISTEM']}',
        'npk' => '{row['NPK API']}',
        'nama' => '{row['NAME']}',
        'password' => bcrypt('1234'),
        'no_telp' => '$this->generateRandomPhoneNumber(),
        'section_id' => '{row['SECTION']}',
        'department_id' => '{row['DEPT']}',
        'division_id' => '{row['DIVISI']}',
        'role_id' => '7',  // default
    }},
    """
    print(php_array)

