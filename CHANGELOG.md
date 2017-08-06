## [Unreleased]
### Added
- Add events before and after feature is searched / requested @chowanski
- Add optional context object for features (breaking change!) @chowanski
- [#4] Add possibility for deactivate the annotation subscriber (default: false) @espendiller / @chowanski
- [#5] Add new subscriber for activate features by routing metadata (default: false) @espendiller / @chowanski
- Add 'isActive' method for stashes

### Changed
- Fix phpunit whitelist @chowanski
- Stashes are now explicitly queried for the status of a feature and not every time for all features (breaking change!) @chowanski
- Move tests to root 'tests' directory @chowanski
- Profiler shows inactive features too @chowanski
- Fix readme @chowanski
- Change CookieStash separator from pipe '|' to comma ',' @chowanski

### Removed
- Remove 'getActiveFeatures' method for stashes @chowanski
- Remove FeatureBag / features are now passed directly to ConfigStash @chowanski

## [1.0.4] - 2017-08-07
### Added
- Configuration for enable / disable annotation check @espendiller / @chowanski
- Add feature handling via route metadata @espendiller / @chowanski

### Changed
- Remove feature bag and move logic to ConfigStash @espendiller / @chowanski

## [1.0.3] - 2017-08-03
### Added
- Add error message for annotation subscriber if feature is inactive @chowanski

### Change
- Fix 'Cannot use object of type Closure as array' error @chowanski

## [1.0.2] - 2017-08-02
### Added
- Add getName method in twig extension for supporting twig version < 1.26 @chowanski

## [1.0.1] - 2017-08-02
### Removed
- Removed obsolete repository in composer.json @chowanski

## [1.0.0] - 2017-08-02
### Changed
- Symfony min version downgraded from ^3.2 to ^3.1 @chowanski
- Profiler toolbar hides feature section when 0 features are active
- Fix readme typo @chowanski

## [0.0.1] - 2017-06-23
### Added
- Initial commit and relase @chowanski
- Add annotation feature check @chowanski
- Add twig extension for active check @chowanski
- Add FilterManager @chowanski
- Add StashBag / FeatureBag @chowanski
- Add Profiler @chowanski
- Add ConfigStash @chowanski
- Add CookieStash @chowanski