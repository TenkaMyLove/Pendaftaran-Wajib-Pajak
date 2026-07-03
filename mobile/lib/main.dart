import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:skeletonizer/skeletonizer.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'E-Pendaftaran Pajak',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        brightness: Brightness.dark,
        primarySwatch: Colors.amber,
        scaffoldBackgroundColor: const Color(0xFF080D1A),
        cardColor: const Color(0xFF1E293B),
        dialogBackgroundColor: const Color(0xFF1E293B),
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFF0F172A),
          elevation: 0,
        ),
        inputDecorationTheme: InputDecorationTheme(
          filled: true,
          fillColor: const Color(0xFF030712),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8.0),
            borderSide: const BorderSide(color: Color(0xFF334155)),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8.0),
            borderSide: const BorderSide(color: Color(0xFF1E293B)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8.0),
            borderSide: const BorderSide(color: Colors.amber, width: 2),
          ),
          labelStyle: const TextStyle(color: Color(0xFF94A3B8)),
          hintStyle: const TextStyle(color: Color(0xFF475569)),
        ),
      ),
      home: const MainScreen(),
    );
  }
}

class MainScreen extends StatefulWidget {
  const MainScreen({super.key});

  @override
  State<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  String _apiUrl = kIsWeb ? 'http://localhost:8000/api' : 'http://10.0.2.2:8000/api';
  int? _registeredId;
  Map<String, dynamic>? _myRegistrationData;
  bool _isLoading = true;

  String get _defaultApiUrl {
    if (kIsWeb) {
      final host = Uri.base.host;
      final activeHost = host.isNotEmpty ? host : 'localhost';
      return 'http://$activeHost:8000/api';
    } else {
      return 'http://10.0.2.2:8000/api';
    }
  }

  @override
  void initState() {
    super.initState();
    _apiUrl = _defaultApiUrl;
    _loadRegistrationState();
  }

  Future<void> _loadRegistrationState() async {
    setState(() {
      _isLoading = true;
    });

    try {
      final prefs = await SharedPreferences.getInstance();
      final savedId = prefs.getInt('registered_id');
      if (savedId != null) {
        _registeredId = savedId;
        await _fetchMyRegistrationDetails(savedId);
      } else {
        setState(() {
          _registeredId = null;
          _myRegistrationData = null;
        });
      }
    } catch (e) {
      debugPrint('Error loading state: $e');
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<void> _fetchMyRegistrationDetails(int id) async {
    try {
      final response = await http.get(Uri.parse('$_apiUrl/registrations/$id'));
      if (response.statusCode == 200) {
        final decoded = json.decode(response.body);
        if (decoded['success'] == true) {
          setState(() {
            _myRegistrationData = decoded['data'];
          });
        }
      } else {
        // Record might be deleted on server, reset local state
        final prefs = await SharedPreferences.getInstance();
        await prefs.remove('registered_id');
        setState(() {
          _registeredId = null;
          _myRegistrationData = null;
        });
      }
    } catch (e) {
      debugPrint('Error fetching details: $e');
    }
  }

  Future<void> _saveRegistrationId(int id) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setInt('registered_id', id);
    setState(() {
      _registeredId = id;
    });
    await _fetchMyRegistrationDetails(id);
  }

  Future<void> _clearRegistrationState() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('registered_id');
    setState(() {
      _registeredId = null;
      _myRegistrationData = null;
    });
  }

  void _showApiSettings() {
    final controller = TextEditingController(text: _apiUrl);
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Pengaturan API Backend'),
          content: TextField(
            controller: controller,
            decoration: const InputDecoration(
              labelText: 'API Base URL',
              hintText: 'http://localhost:8000/api',
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () {
                setState(() {
                  _apiUrl = controller.text;
                });
                Navigator.pop(context);
                if (_registeredId != null) {
                  _fetchMyRegistrationDetails(_registeredId!);
                }
              },
              child: const Text('Simpan'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        appBar: AppBar(
          title: const Text('E-Pendaftaran Pajak'),
        ),
        body: const Center(
          child: CircularProgressIndicator(color: Colors.amber),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Colors.amber, Colors.orange],
                ),
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Text(
                'PJK',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.black),
              ),
            ),
            const SizedBox(width: 10),
            const Text('E-Pendaftaran Pajak'),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.settings),
            onPressed: _showApiSettings,
          ),
          if (_registeredId != null)
            IconButton(
              icon: const Icon(Icons.refresh),
              onPressed: () => _fetchMyRegistrationDetails(_registeredId!),
            ),
        ],
      ),
      body: _myRegistrationData != null
          ? DetailScreen(
              data: _myRegistrationData!,
              onReset: _clearRegistrationState,
            )
          : RegistrationForm(
              apiUrl: _apiUrl,
              onSuccess: (id) => _saveRegistrationId(id),
            ),
    );
  }
}

class RegistrationForm extends StatefulWidget {
  final String apiUrl;
  final Function(int) onSuccess;

  const RegistrationForm({
    super.key,
    required this.apiUrl,
    required this.onSuccess,
  });

  @override
  State<RegistrationForm> createState() => _RegistrationFormState();
}

class _RegistrationFormState extends State<RegistrationForm> {
  final _formKey = GlobalKey<FormState>();

  final _npwpController = TextEditingController();
  final _namaController = TextEditingController();
  final _ktpController = TextEditingController();
  final _alamatKtpController = TextEditingController();
  final _tempatLahirController = TextEditingController();
  final _emailController = TextEditingController();
  final _hpController = TextEditingController();
  final _telpPerusahaanController = TextEditingController();

  DateTime? _tanggalLahir;
  String _jenisKelamin = 'Laki-laki';
  String _jenisNpwp = 'Orang Pribadi';
  String _kependudukan = 'Dalam Negeri';

  bool _isSubmitting = false;

  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now().subtract(const Duration(days: 6570)), // 18 years ago
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
    );
    if (picked != null && picked != _tanggalLahir) {
      setState(() {
        _tanggalLahir = picked;
      });
    }
  }

  void _resetForm() {
    _npwpController.clear();
    _namaController.clear();
    _ktpController.clear();
    _alamatKtpController.clear();
    _tempatLahirController.clear();
    _emailController.clear();
    _hpController.clear();
    _telpPerusahaanController.clear();
    setState(() {
      _tanggalLahir = null;
      _jenisKelamin = 'Laki-laki';
      _jenisNpwp = 'Orang Pribadi';
      _kependudukan = 'Dalam Negeri';
    });
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;
    if (_tanggalLahir == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pilih tanggal lahir terlebih dahulu')),
      );
      return;
    }

    setState(() {
      _isSubmitting = true;
    });

    final payload = {
      'npwp': _npwpController.text,
      'nama_lengkap': _namaController.text,
      'no_ktp': _ktpController.text,
      'alamat_ktp': _alamatKtpController.text,
      'tempat_lahir': _tempatLahirController.text,
      'tanggal_lahir': _tanggalLahir!.toIso8601String().substring(0, 10),
      'jenis_kelamin': _jenisKelamin,
      'email': _emailController.text,
      'no_hp': _hpController.text,
      'no_telp_perusahaan': _telpPerusahaanController.text,
      'jenis_npwp': _jenisNpwp,
      'kependudukan': _kependudukan,
    };

    try {
      final response = await http.post(
        Uri.parse('${widget.apiUrl}/registrations'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode(payload),
      );

      final decoded = json.decode(response.body);

      if (response.statusCode == 201 || decoded['success'] == true) {
        final id = decoded['data']['id'] as int;
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Pendaftaran Berhasil Diajukan!')),
          );
          _resetForm();
          widget.onSuccess(id);
        }
      } else {
        if (mounted) {
          String errMsg = decoded['message'] ?? 'Gagal menyimpan data';
          if (decoded['errors'] != null) {
            errMsg += ': ${decoded['errors'].toString()}';
          }
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(errMsg)),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error saat menyimpan: $e')),
        );
      }
    } finally {
      setState(() {
        _isSubmitting = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Card(
          color: const Color(0xFF111827),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Text(
                  'FORM PENDAFTARAN WAJIB PAJAK',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    letterSpacing: 1.1,
                  ),
                ),
                const SizedBox(height: 24),

                // NPWP
                TextFormField(
                  controller: _npwpController,
                  decoration: const InputDecoration(labelText: 'Nomor Pokok Wajib Pajak (NPWP)'),
                  validator: (value) => value == null || value.isEmpty ? 'NPWP wajib diisi' : null,
                ),
                const SizedBox(height: 16),

                // Nama Lengkap
                TextFormField(
                  controller: _namaController,
                  decoration: const InputDecoration(labelText: 'Nama Lengkap Asli'),
                  validator: (value) => value == null || value.isEmpty ? 'Nama lengkap wajib diisi' : null,
                ),
                const SizedBox(height: 16),

                // No. KTP
                TextFormField(
                  controller: _ktpController,
                  decoration: const InputDecoration(labelText: 'No. KTP'),
                  validator: (value) => value == null || value.isEmpty ? 'No. KTP wajib diisi' : null,
                ),
                const SizedBox(height: 16),

                // Alamat KTP
                TextFormField(
                  controller: _alamatKtpController,
                  maxLines: 3,
                  decoration: const InputDecoration(labelText: 'Alamat KTP'),
                  validator: (value) => value == null || value.isEmpty ? 'Alamat KTP wajib diisi' : null,
                ),
                const SizedBox(height: 16),

                // TTL
                Row(
                  children: [
                    Expanded(
                      flex: 4,
                      child: TextFormField(
                        controller: _tempatLahirController,
                        decoration: const InputDecoration(labelText: 'Tempat Lahir'),
                        validator: (value) => value == null || value.isEmpty ? 'Tempat lahir wajib' : null,
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      flex: 5,
                      child: InkWell(
                        onTap: () => _selectDate(context),
                        child: InputDecorator(
                          decoration: const InputDecoration(labelText: 'Tanggal Lahir'),
                          child: Text(
                            _tanggalLahir == null
                                ? 'Pilih Tanggal'
                                : '${_tanggalLahir!.day}-${_tanggalLahir!.month}-${_tanggalLahir!.year}',
                            style: const TextStyle(fontSize: 14),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 16),

                // Jenis Kelamin
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Jenis Kelamin', style: TextStyle(color: Color(0xFF94A3B8), fontSize: 12)),
                    Row(
                      children: [
                        Expanded(
                          child: RadioListTile<String>(
                            title: const Text('Laki-laki', style: TextStyle(fontSize: 14)),
                            value: 'Laki-laki',
                            groupValue: _jenisKelamin,
                            activeColor: Colors.amber,
                            onChanged: (val) => setState(() => _jenisKelamin = val!),
                          ),
                        ),
                        Expanded(
                          child: RadioListTile<String>(
                            title: const Text('Perempuan', style: TextStyle(fontSize: 14)),
                            value: 'Perempuan',
                            groupValue: _jenisKelamin,
                            activeColor: Colors.amber,
                            onChanged: (val) => setState(() => _jenisKelamin = val!),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                const SizedBox(height: 8),

                // Email
                TextFormField(
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  decoration: const InputDecoration(labelText: 'Alamat E-Mail'),
                  validator: (value) => value == null || value.isEmpty || !value.contains('@')
                      ? 'Email valid wajib diisi'
                      : null,
                ),
                const SizedBox(height: 16),

                // No. HP
                TextFormField(
                  controller: _hpController,
                  keyboardType: TextInputType.phone,
                  decoration: const InputDecoration(labelText: 'No. Hp Aktif'),
                  validator: (value) => value == null || value.isEmpty ? 'No. HP wajib diisi' : null,
                ),
                const SizedBox(height: 16),

                // No Telp Perusahaan
                TextFormField(
                  controller: _telpPerusahaanController,
                  keyboardType: TextInputType.phone,
                  decoration: const InputDecoration(labelText: 'No. Telp Perusahaan Aktif'),
                  validator: (value) => value == null || value.isEmpty ? 'No. Telp Perusahaan wajib' : null,
                ),
                const SizedBox(height: 16),

                // Jenis NPWP
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Kategori Wajib Pajak (Jenis NPWP)', style: TextStyle(color: Color(0xFF94A3B8), fontSize: 12)),
                    RadioListTile<String>(
                      title: const Text('Orang Pribadi', style: TextStyle(fontSize: 14)),
                      value: 'Orang Pribadi',
                      groupValue: _jenisNpwp,
                      activeColor: Colors.amber,
                      onChanged: (val) => setState(() => _jenisNpwp = val!),
                    ),
                    RadioListTile<String>(
                      title: const Text('Badan Usaha / Korporasi', style: TextStyle(fontSize: 14)),
                      value: 'Badan',
                      groupValue: _jenisNpwp,
                      activeColor: Colors.amber,
                      onChanged: (val) => setState(() => _jenisNpwp = val!),
                    ),
                    RadioListTile<String>(
                      title: const Text('Bentuk Usaha Tetap (BUT)', style: TextStyle(fontSize: 14)),
                      value: 'BUT',
                      groupValue: _jenisNpwp,
                      activeColor: Colors.amber,
                      onChanged: (val) => setState(() => _jenisNpwp = val!),
                    ),
                  ],
                ),
                const SizedBox(height: 8),

                const Divider(color: Color(0xFF334155)),

                // Kependudukan
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Kependudukan', style: TextStyle(color: Color(0xFF94A3B8), fontSize: 12)),
                    Row(
                      children: [
                        Expanded(
                          child: RadioListTile<String>(
                            title: const Text('Dalam Negeri', style: TextStyle(fontSize: 14)),
                            value: 'Dalam Negeri',
                            groupValue: _kependudukan,
                            activeColor: Colors.amber,
                            onChanged: (val) => setState(() => _kependudukan = val!),
                          ),
                        ),
                        Expanded(
                          child: RadioListTile<String>(
                            title: const Text('Luar Negeri', style: TextStyle(fontSize: 14)),
                            value: 'Luar Negeri',
                            groupValue: _kependudukan,
                            activeColor: Colors.amber,
                            onChanged: (val) => setState(() => _kependudukan = val!),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                const SizedBox(height: 24),

                // Action Buttons
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    OutlinedButton(
                      onPressed: _resetForm,
                      style: OutlinedButton.styleFrom(
                        side: const BorderSide(color: Color(0xFF334155)),
                        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                      ),
                      child: const Text('Batal', style: TextStyle(color: Color(0xFF94A3B8))),
                    ),
                    const SizedBox(width: 12),
                    ElevatedButton(
                      onPressed: _isSubmitting ? null : _submitForm,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.amber,
                        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                      ),
                      child: _isSubmitting
                          ? const SizedBox(
                              height: 20,
                              width: 20,
                              child: CircularProgressIndicator(strokeWidth: 2, color: Colors.black),
                            )
                          : const Text('Simpan', style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold)),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class DetailScreen extends StatelessWidget {
  final Map<String, dynamic> data;
  final VoidCallback onReset;

  const DetailScreen({
    super.key,
    required this.data,
    required this.onReset,
  });

  @override
  Widget build(BuildContext context) {
    final status = data['status'] ?? 'Pending';
    final isVerified = status == 'Verified';
    final isRejected = status == 'Rejected';

    Color bannerColor;
    Color textBannerColor;
    String bannerText;
    IconData bannerIcon;

    if (isVerified) {
      bannerColor = const Color(0xFF065F46); // emerald-800
      textBannerColor = const Color(0xFF34D399); // emerald-400
      bannerText = 'Pendaftaran Disetujui / Terverifikasi';
      bannerIcon = Icons.check_circle;
    } else if (isRejected) {
      bannerColor = const Color(0xFF991B1B); // red-800
      textBannerColor = const Color(0xFFF87171); // red-400
      bannerText = 'Pendaftaran Ditolak';
      bannerIcon = Icons.error;
    } else {
      bannerColor = const Color(0xFF78350F); // amber-900
      textBannerColor = const Color(0xFFFBBF24); // amber-400
      bannerText = 'Menunggu Verifikasi Admin';
      bannerIcon = Icons.hourglass_empty;
    }

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. Status Banner at Top
          Container(
            padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 16),
            decoration: BoxDecoration(
              color: bannerColor,
              borderRadius: BorderRadius.circular(30),
              border: Border.all(color: textBannerColor.withOpacity(0.3)),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(bannerIcon, color: textBannerColor, size: 16),
                const SizedBox(width: 8),
                Text(
                  bannerText,
                  style: TextStyle(
                    color: textBannerColor,
                    fontSize: 13,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // 2. Kartu Wajib Pajak (Tax Card Mockup)
          Container(
            height: 200,
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [Color(0xFF1E293B), Color(0xFF0F172A)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: Colors.amber.withOpacity(0.2)),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.3),
                  blurRadius: 10,
                  offset: const Offset(0, 5),
                ),
              ],
            ),
            child: Stack(
              children: [
                // Subtle Card Background Texture/Logo Placeholder
                Positioned(
                  right: -20,
                  bottom: -20,
                  child: Opacity(
                    opacity: 0.05,
                    child: Container(
                      width: 150,
                      height: 150,
                      decoration: const BoxDecoration(
                        color: Colors.white,
                        shape: BoxShape.circle,
                      ),
                    ),
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.all(20.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text(
                            'KARTU WAJIB PAJAK',
                            style: TextStyle(
                              fontSize: 12,
                              fontWeight: FontWeight.bold,
                              color: Colors.amber,
                              letterSpacing: 1.5,
                            ),
                          ),
                          Icon(Icons.qr_code, color: Colors.amber.withOpacity(0.5), size: 24),
                        ],
                      ),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            data['nama_lengkap'] ?? '',
                            style: const TextStyle(
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                              color: Colors.white,
                              letterSpacing: 1.0,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            'ID/NIK: ${data['no_ktp'] ?? ''}',
                            style: TextStyle(
                              fontSize: 12,
                              color: Colors.white.withOpacity(0.6),
                            ),
                          ),
                        ],
                      ),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'NOMOR NPWP',
                            style: TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.w500,
                              color: Colors.white.withOpacity(0.5),
                            ),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            isVerified ? (data['npwp'] ?? '') : 'BELUM DIVERIFIKASI',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: isVerified ? Colors.amberAccent : Colors.grey,
                              letterSpacing: 1.2,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 24),

          // 3. Informasi Detail Pendaftar
          Card(
            color: const Color(0xFF111827),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Informasi Detail Pendaftar',
                    style: TextStyle(
                      fontSize: 15,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 16),
                  _buildDetailRow('Email', data['email'] ?? ''),
                  _buildDetailRow('Telepon (HP)', data['no_hp'] ?? ''),
                  _buildDetailRow(
                    'Kategori Wajib Pajak',
                    data['jenis_npwp'] == 'Badan'
                        ? 'Badan Usaha / Korporasi'
                        : data['jenis_npwp'] == 'BUT'
                            ? 'Bentuk Usaha Tetap (BUT)'
                            : (data['jenis_npwp'] ?? ''),
                  ),
                  _buildDetailRow('Kependudukan', data['kependudukan'] ?? ''),
                  _buildDetailRow('Tempat, Tanggal Lahir', '${data['tempat_lahir'] ?? ''}, ${data['tanggal_lahir'] ?? ''}'),
                  _buildDetailRow('Alamat KTP', data['alamat_ktp'] ?? ''),
                  if (data['no_telp_perusahaan'] != null && data['no_telp_perusahaan'].isNotEmpty)
                    _buildDetailRow('Telepon Perusahaan', data['no_telp_perusahaan']),
                ],
              ),
            ),
          ),
          const SizedBox(height: 20),

          // 4. Reset Button to allow re-registering
          TextButton.icon(
            onPressed: onReset,
            icon: const Icon(Icons.logout, color: Colors.grey, size: 16),
            label: const Text(
              'Reset / Daftarkan Baru',
              style: TextStyle(color: Colors.grey, fontSize: 13),
            ),
            style: TextButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 12),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 11,
              color: Colors.grey,
              fontWeight: FontWeight.w500,
            ),
          ),
          const SizedBox(height: 3),
          Text(
            value,
            style: const TextStyle(
              fontSize: 14,
              color: Colors.white,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }
}
