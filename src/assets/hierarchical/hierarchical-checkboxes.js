/**

Hierarchical Checkboxes

author: Anil Maharjan

USAGE:

Template Construction:
User ROOT template and Nest as many NODE templates 

ROOT:

<div class="hierarchy-checkboxes" rel="test">
  <input class="hierarchy-root-checkbox" type="checkbox">
  <label class="hierarchy-root-label">Root</label>
  <div class="hierarchy-root-child hierarchy-node">
   {{ NODE TEMPLATE HERE }}
  </div>
</div>

NODE:

<div class="hierarchy-node [leaf]">
  <input class="hierarchy-checkbox" type="checkbox">
  <label class="hierarchy-label">[Title]</label>
  {{ NODE TEMPLATE HERE }}
</div> 



// Basic Example Template
<div class="hierarchy-checkboxes" rel="test">
  <input class="hierarchy-root-checkbox" type="checkbox">
  <label class="hierarchy-root-label">Root</label>
  <div class="hierarchy-root-child hierarchy-node">
   <div class="hierarchy-node leaf">
      <input class="hierarchy-checkbox" type="checkbox">
      <label class="hierarchy-label">Markets</label>
      <div class="hierarchy-node leaf">
        <input class="hierarchy-checkbox" type="checkbox">
        <label class="hierarchy-label">Markets</label>
      </div> 
    </div> 
    <div class="hierarchy-node leaf">
      <input class="hierarchy-checkbox" type="checkbox">
      <label class="hierarchy-label">Markets</label>
    </div> 
  </div>
</div>


API:

EVENTS:

1. checkboxesUpdate:
  Triggers whenever the check/uncheck tasks complete withing the hierarchical checkboxes

Example:
jQuery('.hierarchy-checkboxes[rel=IDENTIFIER]').on('checkboxesUpdate',function(){
  console.log("Changed!");
});


**/

function initHierarchicalCheckboxes(selector) {
  const $el = selector ? jQuery(selector) : jQuery(document);
  $el.find(".hierarchy-root-child div div").hide().parent().removeClass("child-expanded");
  $el.find(".hierarchy-checkboxes, .hierarchy-root-child, .hierarchy-node").each(function () {
    const $this = jQuery(this);
    if ($this.find("> .expand-collapse-button").length === 0) {
      $this.prepend('<div class="expand-collapse-button"></div>');
    }
  });

  // Update parent checkbox states based on children checked state
  $el.find(".hierarchy-root-child > .hierarchy-node").each(function() {
      const $middleNode = jQuery(this);
      const $middleCheckbox = $middleNode.children("input.hierarchy-checkbox");
      const $leafCheckboxes = $middleNode.find(".leaf input.hierarchy-checkbox");
      if ($leafCheckboxes.length > 0) {
          const allChecked = $leafCheckboxes.length === $leafCheckboxes.filter(":checked").length;
          $middleCheckbox.prop("checked", allChecked);
      }
  });

  // Update root checkbox states based on children checked state
  $el.find(".hierarchy-checkboxes").each(function() {
      const $root = jQuery(this);
      const rel = $root.attr("rel");
      const $rootChild = jQuery(".hierarchy-root-child[rel=" + rel + "]");
      const $rootCheckbox = $root.find(".hierarchy-root-checkbox");
      const $allCheckboxes = $rootChild.find("input.hierarchy-checkbox");
      if ($allCheckboxes.length > 0) {
          const allChecked = $allCheckboxes.length === $allCheckboxes.filter(":checked").length;
          $rootCheckbox.prop("checked", allChecked);
      }
  });
}

window.initHierarchicalCheckboxes = initHierarchicalCheckboxes;

jQuery(document).ready(function () {
  jQuery(".hierarchy-checkboxes .hierarchy-root-child")
    .attr("rel", function () {
      const $this = jQuery(this);
      $this.attr("rel", $this.closest(".hierarchy-checkboxes").attr("rel"));
    })
    .appendTo("body")
    .hide();

  initHierarchicalCheckboxes(document);

  jQuery(document).on("click", ".hierarchy-root-label", function () {
    const $this = jQuery(this);
    const $thisNode = $this.parent();
    const rel = $thisNode.attr("rel");
    const $rootChild = jQuery(".hierarchy-root-child[rel=" + rel + "]");
    if (!$thisNode.hasClass("child-expanded")) {
      $thisNode.addClass("child-expanded");
      const thisPos = $thisNode.offset();

      $rootChild
        .css({ left: 20, top: 50})
        .slideDown("fast");
    } else {
      $rootChild.slideUp("fast", function () {
        $thisNode.removeClass("child-expanded");
      });
    }
  });

  jQuery(document).on("click", ".expand-collapse-button", function () {
      jQuery(this).siblings(".hierarchy-label").click();

    // For root node
    jQuery(this).siblings(".hierarchy-root-label").click();
  });
  jQuery(document).on("change", ".hierarchy-root-checkbox", function () {
    const $this = jQuery(this);
    //$thisNode is parent to current checkbox so it would represent current level node
    const $thisNode = $this.parent();

    const rel = $thisNode.attr("rel"); // Identifier (rel attribute of current hierarchy root)
    const $rootChild = jQuery(".hierarchy-root-child[rel=" + rel + "]"); // The node that contains all the elements of hierarchy;
    $rootChild
      .find("input.hierarchy-checkbox")
      .prop("checked", $this.prop("checked"));
    $thisNode.trigger("checkboxesUpdate");
  });

  // Each node's label toggles the node's child / label's sibling
  jQuery(document).on("click", ".hierarchy-node .hierarchy-label", function () {
    const $this = jQuery(this);
    const $thisNode = $this.parent();
    if (!$thisNode.hasClass("child-expanded")) {
      $thisNode.addClass("child-expanded");
      $this.siblings(".hierarchy-node").slideDown("fast");
    } else {
      $this.siblings(".hierarchy-node").slideUp("fast", function () {
        $thisNode.removeClass("child-expanded");
      });
    }
  });

  // Each node's checkbox toggles the node's child / checkbox's sibling
  jQuery(document).on("change", ".hierarchy-node .hierarchy-checkbox", function () {
    const $this = jQuery(this);
    //$thisNode is parent to current checkbox so it would represent current level node
    const $thisNode = $this.parent();
    const $parentNode = $thisNode.parent(".hierarchy-node");
    const $parentNodeCheckbox = $parentNode.children(
      "input.hierarchy-checkbox"
    );

    const $rootChild = $this.parents(".hierarchy-root-child"); // The node that contains all the elements of hierarchy;
    const rel = $rootChild.attr("rel"); // Identifier (rel attribute of current hierarchy root)
    const $root = jQuery(".hierarchy-checkboxes[rel=" + rel + "]");
    const $rootCheckbox = jQuery(
      ".hierarchy-checkboxes[rel=" + rel + "] .hierarchy-root-checkbox"
    );

    // take care of children | Easy one
    $this
      .siblings(".hierarchy-node")
      .find("input.hierarchy-checkbox")
      .prop("checked", $this.prop("checked"));

    //take care of parents | tough one
    if (!$this.prop("checked")) {
      // If unchecked uncheck all the ancestors
      $thisNode
        .parents(".hierarchy-node")
        .children("input.hierarchy-checkbox") 
        .prop("checked", $this.prop("checked"));
      // also uncheck the root
      $rootCheckbox.prop("checked", false);
    } else {
      // If checked check for the siblings state and check the parent if all siblings are checked too
      const allCheckboxesInCurrentDepth = $parentNode.find(
        ".hierarchy-node .hierarchy-checkbox"
      ).length;
      const allCheckedCheckboxesInCurrentDepth = $parentNode.find(
        ".hierarchy-node .hierarchy-checkbox:checked"
      ).length;
      // all nodes in and below siblings are checked
      if (allCheckboxesInCurrentDepth === allCheckedCheckboxesInCurrentDepth) {
        // check the parent
        if ($parentNodeCheckbox.length) {
          $parentNodeCheckbox.prop("checked", true);
        } else {
          $rootCheckbox.prop("checked", true);
        }
      }
    }
    $root.trigger("checkboxesUpdate", [
      $rootChild.find(".hierarchy-checkbox:checked")
    ]);
  });
});
