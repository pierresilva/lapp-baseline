<?php

namespace pierresilva\TranslationLoader;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class LanguageLine extends Model
{
    /** @var array */
    public $translatable = ['text'];

    /** @var array */
    public $guarded = ['id'];

    /** @var array */
    protected $casts = ['text' => 'array'];

    public static function boot()
    {
        parent::boot();

        $flushGroupCache = function (LanguageLine $languageLine) {
            $languageLine->flushGroupCache();
        };

        static::saved($flushGroupCache);
        static::deleted($flushGroupCache);
    }

    public static function getTranslationsForGroup(string $locale, string $group = null): array
    {

        return Cache::rememberForever(static::getCacheKey($locale, $group), function () use ($group, $locale) {
            return static::query()
                    ->where(function($query) use ($group) {
                        if ($group) {
                            $query->where('group', $group);
                        }
                    })
                    ->get()
                    ->reduce(function ($lines, LanguageLine $languageLine) use ($locale) {
                        $translation = $languageLine->getTranslation($locale);
                        if ($translation !== null) {
                            array_set($lines, "{$languageLine->group}." . $languageLine->key, $translation);
                        }

                        return $lines;
                    }) ?? [];
        });
    }

    public function getTranslations(string $locale): array
    {
        return $this->query()->get()->toArray();
    }

    public static function getCacheKey(string $locale, string $group = null): string
    {
        return "pierresilva.translation-loader". $group ? ".{$group}" : "" . ".{$locale}";
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getTranslation(string $locale): ?string
    {
        if (!isset($this->text[$locale])) {
            $fallback = config('app.fallback_locale');

            return $this->text[$fallback] ?? null;
        }

        return $this->text[$locale];
    }

    /**
     * @param string $locale
     * @param string $value
     *
     * @return $this
     */
    public function setTranslation(string $locale, string $value)
    {
        $this->text = array_merge($this->text ?? [], [$locale => $value]);

        return $this;
    }

    public function flushGroupCache()
    {
        foreach ($this->getTranslatedLocales() as $locale) {
            Cache::forget(static::getCacheKey($locale, $this->group));
        }

        Cache::forget(static::getCacheKey($locale));
    }

    protected function getTranslatedLocales(): array
    {
        return array_keys($this->text);
    }
}
