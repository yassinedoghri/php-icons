# CONTRIBUTING

> **This guide serves to set clear expectations for everyone involved with the
> project so that we can improve it together while also creating a welcoming
> space for everyone to participate. Following these guidelines will help ensure
> a positive experience for contributors and maintainers.**

_Pull requests, bug reports, and all other forms of contribution are welcomed
and highly encouraged!_ :octocat:

## 📥 Opening an Issue

Before
[creating an issue](https://help.github.com/en/github/managing-your-work-on-github/creating-an-issue),
check if you are using the latest version of the project. If you are not
up-to-date, see if updating fixes your issue first.

### 🔒 Reporting Security Issues

Review our [Security Policy](./SECURITY.md). **Do not** file a public issue for
security vulnerabilities.

### 🪲 Bug Reports and Other Issues

A great way to contribute to the project is to send a detailed issue when you
encounter a problem. We always appreciate a well-written, thorough bug report.
:v:

In short, since you are most likely a developer, **provide a ticket that you
would like to receive**.

- **Do not open a duplicate issue!** Search through existing issues to see if
  your issue has previously been reported. If your issue exists, comment with
  any additional information you have. You may simply note "I have this problem
  too", which helps prioritize the most common problems and requests.

- **Prefer using
  [reactions](https://github.blog/2016-03-10-add-reactions-to-pull-requests-issues-and-comments/)**,
  not comments, if you simply want to "+1" an existing issue.

- **Use
  [GitHub-flavored Markdown](https://help.github.com/en/github/writing-on-github/basic-writing-and-formatting-syntax).**
  Especially put code blocks and console outputs in backticks (```). This
  improves readability.

## 💌 Feature Requests

Feature requests are welcome! While we will consider all requests, we cannot
guarantee your request will be accepted. We want to avoid
[feature creep](https://en.wikipedia.org/wiki/Feature_creep). Your idea may be
great, but also out-of-scope for the project. If accepted, we cannot make any
commitments regarding the timeline for implementation and release. However, you
are welcome to submit a pull request to help!

- **Do not open a duplicate feature request.** Search for existing feature
  requests first. If you find your feature (or one very similar) previously
  requested, comment on that issue.

- Be precise about the proposed outcome of the feature and how it relates to
  existing features. Include implementation details if possible.

## 🔍 Triaging Issues

You can triage issues which may include reproducing bug reports or asking for
additional information, such as version numbers or reproduction instructions.
Any help you can provide to quickly resolve an issue is very much appreciated!

## 🔁 Submitting Pull Requests

We **love** pull requests! Before
[forking the repo](https://help.github.com/en/github/getting-started-with-github/fork-a-repo)
and
[creating a pull request](https://help.github.com/en/github/collaborating-with-issues-and-pull-requests/proposing-changes-to-your-work-with-pull-requests)
for non-trivial changes, it is usually best to first open an issue to discuss
the changes, or discuss your intended approach for solving the problem in the
comments for an existing issue.

_Note: All contributions will be licensed under the project's license._

- **Smaller is better.** Submit **one** pull request per bug fix or feature. A
  pull request should contain isolated changes pertaining to a single bug fix or
  feature implementation. **Do not** refactor or reformat code that is unrelated
  to your change. It is better to **submit many small pull requests** rather
  than a single large one. Enormous pull requests will take enormous amounts of
  time to review, or may be rejected altogether.

- **Coordinate bigger changes.** For large and non-trivial changes, open an
  issue to discuss a strategy with the maintainers. Otherwise, you risk doing a
  lot of work for nothing!

- **Prioritize understanding over cleverness.** Write code clearly and
  concisely. Remember that source code usually gets written once and read often.
  Ensure the code is clear to the reader. The purpose and logic should be
  obvious to a reasonably skilled developer, otherwise you should add a comment
  that explains it.

- **Follow existing coding style and conventions.** Keep your code consistent
  with the style, formatting, and conventions in the rest of the code base. When
  possible, these will be enforced with a linter. Consistency makes it easier to
  review and modify in the future.

- **Include test coverage.** Add unit tests or UI tests when possible. Follow
  existing patterns for implementing tests.

- **Update the example project** if one exists to exercise any new functionality
  you have added.

- **Add documentation.** Document your changes with code doc comments or in
  existing guides.

- **Use the repo's default branch.** Branch from and
  [submit your pull request](https://help.github.com/en/github/collaborating-with-issues-and-pull-requests/creating-a-pull-request-from-a-fork)
  to the repo's default branch. Usually this is `main`, but it could be `dev`,
  `develop`, or `master`.

- **[Resolve any merge conflicts](https://help.github.com/en/github/collaborating-with-issues-and-pull-requests/resolving-a-merge-conflict-on-github)**
  that occur.

- **Promptly address any CI failures**. If your pull request fails to build or
  pass tests, please push another commit to fix it.

- **Follow the [pull request template](./.github/PULL_REQUEST_TEMPLATE.md)**

- When writing comments, use properly constructed sentences, including
  punctuation.

- Use [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) and
  follow [SemVer](http://semver.org/).

- Use spaces, not tabs.

### ✅ Code Review

- **Review the code, not the author.** Look for and suggest improvements without
  disparaging or insulting the author. Provide actionable feedback and explain
  your reasoning.

- **You are not your code.** When your code is critiqued, questioned, or
  constructively criticized, remember that you are not your code. Do not take
  code review personally.

- **Always do your best.** No one writes bugs on purpose. Do your best, and
  learn from your mistakes.

- Kindly note any violations to the guidelines specified in this document.

### 💅 Coding Style

Consistency is the most important. Following the existing style, formatting, and
naming conventions of the file you are modifying and of the overall project.
Failure to do so will result in a prolonged review process that has to focus on
updating the superficial aspects of your code, rather than improving its
functionality and performance.

For example, if all private properties are prefixed with an underscore `_`, then
new ones you add should be prefixed in the same way. Or, if methods are named
using camelcase, like `thisIsMyNewMethod`, then do not diverge from that by
writing `this_is_my_new_method`. You get the idea. If in doubt, please ask or
search the codebase for something similar.

When possible, style and format will be enforced with a linter.

## ⚙️ Setup project

### Requirements

- **PHP [8.1+](https://php.net/releases/)**

### Installation

1. Clone project
2. Install dependencies

```bash
composer install
```

<details>
<summary>
<h3>Commands</h3>
</summary>

Keep a modern codebase with
[**symplify/easy-coding-standard**](https://github.com/easy-coding-standard/easy-coding-standard)

```bash
composer style:fix
```

⚗️ Run static analysis using
[**PHPStan**](https://phpstan.org/user-guide/command-line-usage)

```bash
composer phpstan
```

✅ Run unit tests using [**PEST**](https://pestphp.com/docs/writing-tests)

```bash
composer pest
```

💽 Analyzes your codebase and generates a report of your
[type coverage](https://pestphp.com/docs/type-coverage) using
[**PEST**](https://pestphp.com/docs/writing-tests)

```bash
composer pest:type-coverage
```

📈 Expose [code coverage](https://pestphp.com/docs/test-coverage) using
[**PEST**](https://pestphp.com/docs/test-coverage) (_require XDEBUG in
`coverage` mode_)

```bash
composer pest:coverage
```

🚀 Run the entire test suite

```bash
composer test
```

🧰 Update dependencies and validate `composer.json`

```bash
composer core:update
```

✅ Run refactors using [**Rector**](https://getrector.com/documentation)

```bash
composer rector:fix
```

</details>

## 🏅 Contributor Agreement

By making a contribution to this project, I certify that I agree to the
[Developer's Certificate of Origin 1.1](https://developercertificate.org/).

---

_This guide is heavily inspired by
[jessesquires](https://github.com/jessesquires/.github) default community health
files._
