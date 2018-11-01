## [3.1.1]
### Changed
- \#45 Replace `symfony/framework-bundle` and `doctrine/common` with following dependencies: 
    - `doctrine/annotations`
    - `symfony/dependency-injection`
    - `symfony/yaml`
    - `symfony/config`
    - `symfony/http-kernel`
    - `twig/twig`

### Removed
- \#45 Remove using `ClassUtils` for getting controller class

## [3.1.0]
### Fix
- \#27 Fix route xml in documentation @migo315

### Changed
- \#35 Swap own cookie activator with the new flagception sdk cookie activator @migo315
- Refactor profiler and data collector  @migo315
- Swap old `ProfilerChainActivator` with new `TraceableChainActivator` @migo315
- Update [Flagception SDK](https://packagist.org/packages/flagception/flagception) to version 1.3.0 @migo315

### Added
- \#26 Add feature name advice in documentation @migo315
- Add `php-mock` as dev dependency and add missing contentful configurator test @migo315
- \#31 Add support for auto configuration for `FeatureActivatorInterface` @migo315
- \#32 Add support for auto configuration for `ContectDecoratorInterface` @migo315
- Add caching option for `ContentfulActivator` @migo315
- Add configuration for the new [DatabaseActivator](https://packagist.org/packages/flagception/database-activator) @migo315

### Removed
- Remove unneeded models and bags (just internal stuff) @migo315

## [3.0.1]
### Fix
- Add service alias for `Flagception\Manager\FeatureManagerInterface` for fixing autowiring @hanishsingla

## [3.0.0]
### Refactored
- Complete refactoring and renaming to `flagception` @migo315
- See [Upgrade from 2.x](UPGRADE-3.0.md)

## [2.1.2] - 2017-11-13
### Fix
- Bug #13 / Fix bool cast for configuration @teiling88

## [2.1.1] - 2017-11-09
### Fix
- Fix variables for configuration @RedactedProfile

## [2.1.0] - 2017-10-26
### Added
- Add ContextDecoratorInterface, CompilerPass and Tag for modify the context object globally @migo315
- All context content are available as own variable for expression constraints @migo315
- Add profiler icon @migo315

## [2.0.0] - 2017-10-11
### Added
- Add events before and after feature is searched / requested @migo315
- Add optional context object for features (breaking change!) @migo315
- Add 'isActive' method for stashes @migo315
- Add configuration option for routing metadata subscriber @migo315
- Add configuration for cookie stash separator @migo315
- Add constraints for ConfigStash @migo315

### Changed
- Changed license to MIT @bestit
- Fix phpunit whitelist @migo315
- Stashes are now explicitly queried for the status of a feature and not every time for all features (breaking change!) @migo315
- Move tests to root 'tests' directory @migo315
- Profiler shows inactive features too @migo315
- Profiler shows given context for features @migo315
- Update readme @migo315
- Chang configuration option of annotation subscriber @migo315

### Removed
- Remove 'getActiveFeatures' method for stashes. Use 'isActive' instead @migo315

## [1.0.4] - 2017-08-07
### Added
- Configuration for enable / disable annotation check @espendiller / @migo315
- Add feature handling via route metadata @espendiller / @migo315

### Changed
- Remove feature bag and move logic to ConfigStash @espendiller / @migo315

## [1.0.3] - 2017-08-03
### Added
- Add error message for annotation subscriber if feature is inactive @migo315

### Change
- Fix 'Cannot use object of type Closure as array' error @migo315

## [1.0.2] - 2017-08-02
### Added
- Add getName method in twig extension for supporting twig version < 1.26 @migo315

## [1.0.1] - 2017-08-02
### Removed
- Removed obsolete repository in composer.json @migo315

## [1.0.0] - 2017-08-02
### Changed
- Symfony min version downgraded from ^3.2 to ^3.1 @migo315
- Profiler toolbar hides feature section when 0 features are active
- Fix readme typo @migo315

## [0.0.1] - 2017-06-23
### Added
- Initial commit and relase @migo315
- Add annotation feature check @migo315
- Add twig extension for active check @migo315
- Add FilterManager @migo315
- Add StashBag / FeatureBag @migo315
- Add Profiler @migo315
- Add ConfigStash @migo315
- Add CookieStash @migo315
