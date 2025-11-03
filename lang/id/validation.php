<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan kesalahan default yang digunakan oleh
    | kelas validator.
    |
    */

    'accepted'             => 'Isian :attribute harus diterima.',
    'accepted_if'          => 'Isian :attribute harus diterima ketika :other bernilai :value.',
    'active_url'           => 'Isian :attribute bukan URL yang valid.',
    'after'                => 'Isian :attribute harus tanggal setelah :date.',
    'after_or_equal'       => 'Isian :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha'                => 'Isian :attribute hanya boleh berisi huruf.',
    'alpha_dash'           => 'Isian :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num'            => 'Isian :attribute hanya boleh berisi huruf dan angka.',
    'array'                => 'Isian :attribute harus berupa array.',
    'ascii'                => 'Isian :attribute hanya boleh berisi karakter alfanumerik dan simbol satu-byte.',
    'before'               => 'Isian :attribute harus tanggal sebelum :date.',
    'before_or_equal'      => 'Isian :attribute harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => 'Isian :attribute harus antara :min dan :max.',
        'file'    => 'Isian :attribute harus antara :min dan :max kilobyte.',
        'string'  => 'Isian :attribute harus antara :min dan :max karakter.',
        'array'   => 'Isian :attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean'              => 'Isian :attribute harus bernilai true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'current_password'     => 'Kata sandi salah.',
    'date'                 => 'Isian :attribute bukan tanggal yang valid.',
    'date_equals'          => 'Isian :attribute harus tanggal yang sama dengan :date.',
    'date_format'          => 'Isian :attribute tidak cocok dengan format :format.',
    'decimal'              => 'Isian :attribute harus memiliki :decimal tempat desimal.',
    'declined'             => 'Isian :attribute harus ditolak.',
    'declined_if'          => 'Isian :attribute harus ditolak ketika :other bernilai :value.',
    'different'            => 'Isian :attribute dan :other harus berbeda.',
    'digits'               => 'Isian :attribute harus berupa angka :digits.',
    'digits_between'       => 'Isian :attribute harus antara :min dan :max angka.',
    'dimensions'           => 'Isian :attribute memiliki dimensi gambar tidak valid.',
    'distinct'             => 'Isian :attribute memiliki nilai duplikat.',
    'doesnt_end_with'      => 'Isian :attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with'    => 'Isian :attribute tidak boleh diawali dengan salah satu dari: :values.',
    'email'                => 'Isian :attribute harus berupa alamat email yang valid.',
    'ends_with'            => 'Isian :attribute harus diakhiri dengan salah satu dari: :values.',
    'enum'                 => 'Isian :attribute tidak valid.',
    'exists'               => 'Isian :attribute tidak valid.',
    'file'                 => 'Isian :attribute harus berupa file.',
    'filled'               => 'Isian :attribute wajib diisi.',
    'gt'                   => [
        'numeric' => 'Isian :attribute harus lebih besar dari :value.',
        'file'    => 'Isian :attribute harus lebih besar dari :value kilobyte.',
        'string'  => 'Isian :attribute harus lebih besar dari :value karakter.',
        'array'   => 'Isian :attribute harus memiliki lebih dari :value item.',
    ],
    'gte'                  => [
        'numeric' => 'Isian :attribute harus lebih besar atau sama dengan :value.',
        'file'    => 'Isian :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'string'  => 'Isian :attribute harus lebih besar atau sama dengan :value karakter.',
        'array'   => 'Isian :attribute harus memiliki :value item atau lebih.',
    ],
    'image'                => 'Isian :attribute harus berupa gambar.',
    'in'                   => 'Isian :attribute tidak valid.',
    'in_array'             => 'Isian :attribute tidak terdapat dalam :other.',
    'integer'              => 'Isian :attribute harus berupa bilangan bulat.',
    'ip'                   => 'Isian :attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => 'Isian :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => 'Isian :attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => 'Isian :attribute harus berupa string JSON yang valid.',
    'lowercase'            => 'Isian :attribute harus berupa huruf kecil.',
    'lt'                   => [
        'numeric' => 'Isian :attribute harus kurang dari :value.',
        'file'    => 'Isian :attribute harus kurang dari :value kilobyte.',
        'string'  => 'Isian :attribute harus kurang dari :value karakter.',
        'array'   => 'Isian :attribute harus memiliki kurang dari :value item.',
    ],
    'lte'                  => [
        'numeric' => 'Isian :attribute harus kurang dari atau sama dengan :value.',
        'file'    => 'Isian :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string'  => 'Isian :attribute harus kurang dari atau sama dengan :value karakter.',
        'array'   => 'Isian :attribute tidak boleh memiliki lebih dari :value item.',
    ],
    'mac_address'          => 'Isian :attribute harus berupa alamat MAC yang valid.',
    'max'                  => [
        'numeric' => 'Isian :attribute tidak boleh lebih besar dari :max.',
        'file'    => 'Isian :attribute tidak boleh lebih besar dari :max kilobyte.',
        'string'  => 'Isian :attribute tidak boleh lebih dari :max karakter.',
        'array'   => 'Isian :attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'max_digits'           => 'Isian :attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes'                => 'Isian :attribute harus berupa file bertipe: :values.',
    'mimetypes'            => 'Isian :attribute harus berupa file bertipe: :values.',
    'min'                  => [
        'numeric' => 'Isian :attribute minimal bernilai :min.',
        'file'    => 'Isian :attribute minimal berukuran :min kilobyte.',
        'string'  => 'Isian :attribute minimal :min karakter.',
        'array'   => 'Isian :attribute minimal memiliki :min item.',
    ],
    'min_digits'           => 'Isian :attribute minimal memiliki :min digit.',
    'missing'              => 'Isian :attribute harus kosong.',
    'missing_if'           => 'Isian :attribute harus kosong ketika :other bernilai :value.',
    'missing_unless'       => 'Isian :attribute harus kosong kecuali :other bernilai :value.',
    'missing_with'         => 'Isian :attribute harus kosong ketika :values ada.',
    'missing_with_all'     => 'Isian :attribute harus kosong ketika :values ada.',
    'multiple_of'          => 'Isian :attribute harus kelipatan dari :value.',
    'not_in'               => 'Isian :attribute tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => 'Isian :attribute harus berupa angka.',
    'password'             => [
        'letters' => 'Isian :attribute harus berisi setidaknya satu huruf.',
        'mixed'   => 'Isian :attribute harus berisi setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Isian :attribute harus berisi setidaknya satu angka.',
        'symbols' => 'Isian :attribute harus berisi setidaknya satu simbol.',
        'uncompromised' => 'Isian :attribute telah muncul dalam kebocoran data. Silakan gunakan :attribute lain.',
    ],
    'present'              => 'Isian :attribute harus ada.',
    'present_if'           => 'Isian :attribute harus ada ketika :other bernilai :value.',
    'present_unless'       => 'Isian :attribute harus ada kecuali :other bernilai :value.',
    'present_with'         => 'Isian :attribute harus ada ketika :values ada.',
    'present_with_all'     => 'Isian :attribute harus ada ketika semua :values ada.',
    'prohibited'           => 'Isian :attribute dilarang.',
    'prohibited_if'        => 'Isian :attribute dilarang ketika :other bernilai :value.',
    'prohibited_unless'    => 'Isian :attribute dilarang kecuali :other ada dalam :values.',
    'prohibits'            => 'Isian :attribute melarang :other untuk ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => 'Isian :attribute wajib diisi.',
    'required_array_keys'  => 'Isian :attribute harus berisi entri untuk: :values.',
    'required_if'          => 'Isian :attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => 'Isian :attribute wajib diisi ketika :other diterima.',
    'required_unless'      => 'Isian :attribute wajib diisi kecuali :other ada dalam :values.',
    'required_with'        => 'Isian :attribute wajib diisi ketika :values ada.',
    'required_with_all'    => 'Isian :attribute wajib diisi ketika semua :values ada.',
    'required_without'     => 'Isian :attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => 'Isian :attribute wajib diisi ketika tidak ada satupun dari :values yang ada.',
    'same'                 => 'Isian :attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => 'Isian :attribute harus berukuran :size.',
        'file'    => 'Isian :attribute harus berukuran :size kilobyte.',
        'string'  => 'Isian :attribute harus :size karakter.',
        'array'   => 'Isian :attribute harus berisi :size item.',
    ],
    'starts_with'          => 'Isian :attribute harus diawali dengan salah satu dari: :values.',
    'string'               => 'Isian :attribute harus berupa string.',
    'timezone'             => 'Isian :attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah digunakan.',
    'uploaded'             => 'Gagal mengunggah :attribute.',
    'uppercase'            => 'Isian :attribute harus berupa huruf besar.',
    'url'                  => 'Format :attribute tidak valid.',
    'ulid'                 => 'Isian :attribute harus berupa ULID yang valid.',
    'uuid'                 => 'Isian :attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa Kustom untuk Validasi
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'pesan-kustom',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atribut Kustom
    |--------------------------------------------------------------------------
    |
    | Baris berikut digunakan untuk mengganti nama atribut dengan sesuatu yang
    | lebih mudah dimengerti pengguna.
    |
    */

    'attributes' => [],
];
