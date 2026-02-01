<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';
    
    protected string $view = 'filament.pages.edit-profile';
    
    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $title = 'Edit Profile';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);
    }
    
    public function getTitle(): string
    {
        return 'Edit Profile';
    }
    
    public static function getLabel(): string
    {
        return 'Profile';
    }
    
    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'profile';
    }
    
    public static function getRouteName(?\Filament\Panel $panel = null): string
    {
        $panel = $panel ?? filament()->getCurrentPanel();
        
        return $panel->generateRouteName('pages.profile');
    }
    
    public function getFormSchema(): array
    {
        return [
            Section::make('Informasi Profil')
                ->description('Update informasi profil Anda')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(table: 'users', ignorable: auth()->user()),
                ])
                ->columns(2),
                
            Section::make('Ubah Password')
                ->description('Pastikan akun Anda menggunakan password yang kuat untuk keamanan')
                ->schema([
                    TextInput::make('current_password')
                        ->label('Password Saat Ini')
                        ->password()
                        ->currentPassword()
                        ->revealable()
                        ->dehydrated(false),
                    TextInput::make('password')
                        ->label('Password Baru')
                        ->password()
                        ->rule(Password::default())
                        ->different('current_password')
                        ->confirmed()
                        ->revealable()
                        ->dehydrated(fn ($state) => filled($state)),
                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password Baru')
                        ->password()
                        ->revealable()
                        ->dehydrated(false),
                ])
                ->columns(1),
        ];
    }
    
    protected function getFormStatePath(): ?string
    {
        return 'data';
    }
    
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        $user = auth()->user();
        
        // Update profile
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        
        // Update password if provided
        if (filled($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
            
            Notification::make()
                ->success()
                ->title('Profil dan password berhasil diperbarui')
                ->send();
        } else {
            Notification::make()
                ->success()
                ->title('Profil berhasil diperbarui')
                ->send();
        }
        
        // Refresh form
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => null,
            'password' => null,
            'password_confirmation' => null,
        ]);
    }
}
